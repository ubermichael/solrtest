<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\PaginatorInterface;
use Nines\SolrBundle\Client\Builder;
use Nines\SolrBundle\Query\Result;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Solarium\QueryType\Select\Query\FilterQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @Route("/", name="homepage")
     * @Template
     *
     * @return array
     */
    public function indexAction(Request $request) {
        return [];
    }

    /**
     * @Route("/privacy", name="privacy")
     * @Template
     */
    public function privacyAction(Request $request) : void {
    }

    /**
     * @Route("/solr", name="solr")
     * @Template
     */
    public function solrAction(Request $request, Builder $builder, EntityManagerInterface $em, PaginatorInterface $paginator) {
        $q = $request->query->get('q');
        $filters = $request->query->get('filter', []);
        $page = (int)$request->query->get('page', 1);
        $pageSize = 10; //(int)$this->getParameter('page_size');
        $qr = null;
        $paginated = null;
        if ($q) {
            $client = $builder->build();
            $query = $client->createSelect();

            $query->setStart(($page - 1) * $pageSize);
            $query->setRows($pageSize);

            $query->setQuery($q);
            $query->setQueryDefaultField('content_txt');

            foreach($filters as $key => $values) {
                $terms = join(" or ", $values);
                $query->createFilterQuery('fq_' . $key)->addTag('exclude')->setQuery("{$key}:({$terms})");
            }

            $hl = $query->getHighlighting();
            $hl->setFields('content_txt');
            $hl->setSimplePrefix("<span class='hl'>");
            $hl->setSimplePostfix('</span>');

            $fs = $query->getFacetSet();
            $fs->createFacetField('type')->setField('type_s')->getLocalParameters()->addExcludes(['exclude']);
            $fs->createFacetField('genre')->setField('genres_ss')->getLocalParameters()->addExcludes(['exclude']);

            dump($query);

            $paginated = $paginator->paginate([$client, $query], $page, $pageSize);
            $qr = new Result($paginated->getCustomParameter('result'), $em, $paginator);
        }

        return [
            'q' => $q,
            'qr' => $qr,
            'start' => ($page - 1) * $pageSize + 1,
            'end' => ($page) * $pageSize,
            'pagination' => $paginated,
        ];
    }
}
