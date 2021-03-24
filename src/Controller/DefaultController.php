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
use Nines\SolrBundle\Client\ClientFactory;
use Nines\SolrBundle\Logging\SolrLogger;
use Nines\SolrBundle\Query\QueryBuilder;
use Nines\SolrBundle\Query\Result;
use Nines\SolrBundle\Services\SolrManager;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Solarium\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController implements PaginatorAwareInterface
{
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
    public function solrAction(Request $request, SolrManager $solr, EntityManagerInterface $em) {
        $q = $request->query->get('q');
        $filters = $request->query->get('filter', []);
        $result = null;
        if ($q) {
            $qb = $solr->createQueryBuilder();
            $qb->setQueryString($q);
            $qb->setDefaultField('content_txt');

            foreach ($filters as $key => $values) {
                $qb->addFilter($key, $values);
            }
            $qb->setHighlightFields('content_txt');
            $qb->addFacetField('type', 'type_s');
            $qb->addFacetField('genre', 'genres_ss');
            $query = $qb->getQuery();
            $result = $solr->execute($query, $this->paginator, [
                'page' => (int) $request->query->get('page', 1),
                'pageSize' => (int) $this->getParameter('page_size')
            ]);
        }

        return [
            'q' => $q,
            'result' => $result,
        ];
    }
}
