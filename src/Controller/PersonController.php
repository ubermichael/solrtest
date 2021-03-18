<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\PaginatorInterface;
use Nines\SolrBundle\Logging\SolrLogger;
use Nines\SolrBundle\Query\QueryBuilder;
use Nines\SolrBundle\Query\Result;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/person")
 */
class PersonController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @Route("/", name="person_index", methods={"GET"})
     *
     * @Template
     */
    public function index(Request $request, PersonRepository $personRepository) : array {
        $query = $personRepository->indexQuery();
        $pageSize = (int) $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'people' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="person_search", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function search(Request $request, SolrLogger $logger, QueryBuilder $qb, EntityManagerInterface $em, PaginatorInterface $paginator) {
        $q = $request->query->get('q');
        $filters = $request->query->get('filter', []);
        $filterRanges = $request->query->get('filter_range', []);
        $page = (int) $request->query->get('page', 1);
        $pageSize = (int)$this->getParameter('page_size');
        $qr = null;
        $paginated = null;

        if ($q) {
            $qb->setQueryString($q);
            $qb->setDefaultField("content_txt");

            foreach ($filters as $key => $values) {
                $qb->addFilter($key, $values);
            }

            foreach($filterRanges as $key => $values) {
                foreach($values as $range) {
                    list($start, $end) = explode(" ", $range);
                    $qb->addFilterRange($key, $start, $end);
                }
            }
            $qb->addFilter('type_s', ['Person']);
            $qb->setHighlightFields('content_txt');

            $qb->addFacetField('birth_place', 'birth_place_s');
            $qb->addFacetField('death_place', 'death_place_s');
            $qb->addFacetRange('birth_date', 'birth_date_i', 1600, 2020, 50);
            $query = $qb->getQuery();

            $logger->startQuery($query);
            $paginated = $paginator->paginate([$qb->getClient(), $query], $page, $pageSize);
            $qr = new Result($paginated->getCustomParameter('result'), $em, $paginator);
            $logger->stopQuery();
        }

        return [
            'q' => $q,
            'qr' => $qr,
            'pagination' => $paginated,
        ];
    }

    /**
     * @Route("/typeahead", name="person_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, PersonRepository $personRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($personRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="person_new", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request) {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();
            $this->addFlash('success', 'The new person has been saved.');

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return [
            'person' => $person,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new_popup", name="person_new_popup", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request) {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="person_show", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function show(Person $person) {
        return [
            'person' => $person,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="person_edit", methods={"GET", "POST"})
     *
     * @Template
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, Person $person) {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated person has been saved.');

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return [
            'person' => $person,
            'form' => $form->createView(),
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="person_delete", methods={"DELETE"})
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, Person $person) {
        if ($this->isCsrfTokenValid('delete' . $person->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($person);
            $entityManager->flush();
            $this->addFlash('success', 'The person has been deleted.');
        }

        return $this->redirectToRoute('person_index');
    }
}
