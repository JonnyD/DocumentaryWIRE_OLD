<?php

namespace DW\DWBundle\Manager;

use Knp\Bundle\PaginatorBundle\Definition\PaginatorAware;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;

class PaginateManager
{
    private $paginator;

    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function paginate($entries, $limit, Request $request)
    {
        return $this->paginator->paginate(
            $entries,
            $request->query->get('page', 1)/*page number*/,
            $limit/*limit per page*/
        );
    }

    public function getPaginator()
    {
        return $this->paginator;
    }
}