<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Alias;
use App\Form\AliasType;
use App\Repository\AliasRepository;
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
 * @Route("/alias")
 */
class AliasController extends AbstractController implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * @Route("/", name="alias_index", methods={"GET"})
     *
     * @Template
     */
    public function index(Request $request, AliasRepository $aliasRepository) : array {
        $query = $aliasRepository->indexQuery();
        $pageSize = (int) $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'aliases' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="alias_search", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function search(Request $request, AliasRepository $aliasRepository) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $aliasRepository->searchQuery($q);
            $aliases = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), ['wrap-queries' => true]);
        } else {
            $aliases = [];
        }

        return [
            'aliases' => $aliases,
            'q' => $q,
        ];
    }

    /**
     * @Route("/typeahead", name="alias_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, AliasRepository $aliasRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($aliasRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="alias_new", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request) {
        $alias = new Alias();
        $form = $this->createForm(AliasType::class, $alias);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($alias);
            $entityManager->flush();
            $this->addFlash('success', 'The new alias has been saved.');

            return $this->redirectToRoute('alias_show', ['id' => $alias->getId()]);
        }

        return [
            'alias' => $alias,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new_popup", name="alias_new_popup", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request) {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="alias_show", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function show(Alias $alias) {
        return [
            'alias' => $alias,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="alias_edit", methods={"GET", "POST"})
     *
     * @Template
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, Alias $alias) {
        $form = $this->createForm(AliasType::class, $alias);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated alias has been saved.');

            return $this->redirectToRoute('alias_show', ['id' => $alias->getId()]);
        }

        return [
            'alias' => $alias,
            'form' => $form->createView(),
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="alias_delete", methods={"DELETE"})
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, Alias $alias) {
        if ($this->isCsrfTokenValid('delete' . $alias->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($alias);
            $entityManager->flush();
            $this->addFlash('success', 'The alias has been deleted.');
        }

        return $this->redirectToRoute('alias_index');
    }
}
