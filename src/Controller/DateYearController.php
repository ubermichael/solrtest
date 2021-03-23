<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\DateYear;
use App\Form\DateYearType;
use App\Repository\DateYearRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/date_year")
 */
class DateYearController extends AbstractController implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * @Route("/", name="date_year_index", methods={"GET"})
     *
     * @Template
     */
    public function index(Request $request, DateYearRepository $dateYearRepository) : array {
        $query = $dateYearRepository->indexQuery();
        $pageSize = (int) $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'date_years' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="date_year_search", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function search(Request $request, DateYearRepository $dateYearRepository) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $dateYearRepository->searchQuery($q);
            $dateYears = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), ['wrap-queries' => true]);
        } else {
            $dateYears = [];
        }

        return [
            'date_years' => $dateYears,
            'q' => $q,
        ];
    }

    /**
     * @Route("/typeahead", name="date_year_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, DateYearRepository $dateYearRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($dateYearRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="date_year_new", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request) {
        $dateYear = new DateYear();
        $form = $this->createForm(DateYearType::class, $dateYear);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dateYear);
            $entityManager->flush();
            $this->addFlash('success', 'The new dateYear has been saved.');

            return $this->redirectToRoute('date_year_show', ['id' => $dateYear->getId()]);
        }

        return [
            'date_year' => $dateYear,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new_popup", name="date_year_new_popup", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request) {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="date_year_show", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function show(DateYear $dateYear) {
        return [
            'date_year' => $dateYear,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="date_year_edit", methods={"GET", "POST"})
     *
     * @Template
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, DateYear $dateYear) {
        $form = $this->createForm(DateYearType::class, $dateYear);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated dateYear has been saved.');

            return $this->redirectToRoute('date_year_show', ['id' => $dateYear->getId()]);
        }

        return [
            'date_year' => $dateYear,
            'form' => $form->createView(),
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="date_year_delete", methods={"DELETE"})
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, DateYear $dateYear) {
        if ($this->isCsrfTokenValid('delete' . $dateYear->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($dateYear);
            $entityManager->flush();
            $this->addFlash('success', 'The dateYear has been deleted.');
        }

        return $this->redirectToRoute('date_year_index');
    }
}
