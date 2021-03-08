<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Firm;
use App\Form\FirmType;
use App\Repository\FirmRepository;
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
 * @Route("/firm")
 */
class FirmController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @Route("/", name="firm_index", methods={"GET"})
     *
     * @Template
     */
    public function index(Request $request, FirmRepository $firmRepository) : array {
        $query = $firmRepository->indexQuery();
        $pageSize = (int) $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'firms' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="firm_search", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function search(Request $request, FirmRepository $firmRepository) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $firmRepository->searchQuery($q);
            $firms = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), ['wrap-queries' => true]);
        } else {
            $firms = [];
        }

        return [
            'firms' => $firms,
            'q' => $q,
        ];
    }

    /**
     * @Route("/typeahead", name="firm_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, FirmRepository $firmRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($firmRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="firm_new", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request) {
        $firm = new Firm();
        $form = $this->createForm(FirmType::class, $firm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($firm);
            $entityManager->flush();
            $this->addFlash('success', 'The new firm has been saved.');

            return $this->redirectToRoute('firm_show', ['id' => $firm->getId()]);
        }

        return [
            'firm' => $firm,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new_popup", name="firm_new_popup", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request) {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="firm_show", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function show(Firm $firm) {
        return [
            'firm' => $firm,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="firm_edit", methods={"GET", "POST"})
     *
     * @Template
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, Firm $firm) {
        $form = $this->createForm(FirmType::class, $firm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated firm has been saved.');

            return $this->redirectToRoute('firm_show', ['id' => $firm->getId()]);
        }

        return [
            'firm' => $firm,
            'form' => $form->createView(),
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="firm_delete", methods={"DELETE"})
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, Firm $firm) {
        if ($this->isCsrfTokenValid('delete' . $firm->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($firm);
            $entityManager->flush();
            $this->addFlash('success', 'The firm has been deleted.');
        }

        return $this->redirectToRoute('firm_index');
    }
}
