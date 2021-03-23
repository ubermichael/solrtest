<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Periodical;
use App\Form\PeriodicalType;
use App\Repository\PeriodicalRepository;
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
 * @Route("/periodical")
 */
class PeriodicalController extends AbstractController implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * @Route("/", name="periodical_index", methods={"GET"})
     *
     * @Template
     */
    public function index(Request $request, PeriodicalRepository $periodicalRepository) : array {
        $query = $periodicalRepository->indexQuery();
        $pageSize = (int) $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'periodicals' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="periodical_search", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function search(Request $request, PeriodicalRepository $periodicalRepository) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $periodicalRepository->searchQuery($q);
            $periodicals = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), ['wrap-queries' => true]);
        } else {
            $periodicals = [];
        }

        return [
            'periodicals' => $periodicals,
            'q' => $q,
        ];
    }

    /**
     * @Route("/typeahead", name="periodical_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, PeriodicalRepository $periodicalRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($periodicalRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="periodical_new", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request) {
        $periodical = new Periodical();
        $form = $this->createForm(PeriodicalType::class, $periodical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($periodical);
            $entityManager->flush();
            $this->addFlash('success', 'The new periodical has been saved.');

            return $this->redirectToRoute('periodical_show', ['id' => $periodical->getId()]);
        }

        return [
            'periodical' => $periodical,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new_popup", name="periodical_new_popup", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request) {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="periodical_show", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function show(Periodical $periodical) {
        return [
            'periodical' => $periodical,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="periodical_edit", methods={"GET", "POST"})
     *
     * @Template
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, Periodical $periodical) {
        $form = $this->createForm(PeriodicalType::class, $periodical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated periodical has been saved.');

            return $this->redirectToRoute('periodical_show', ['id' => $periodical->getId()]);
        }

        return [
            'periodical' => $periodical,
            'form' => $form->createView(),
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="periodical_delete", methods={"DELETE"})
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, Periodical $periodical) {
        if ($this->isCsrfTokenValid('delete' . $periodical->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($periodical);
            $entityManager->flush();
            $this->addFlash('success', 'The periodical has been deleted.');
        }

        return $this->redirectToRoute('periodical_index');
    }
}
