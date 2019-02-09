<?php

namespace App\Utils;


use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

trait PaginatorHandlerTrait
{
    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    public function paginate(QueryBuilder $queryBuilder,Request $request,int $limit = 10,string $page_name = 'page',int $page_number = 1)
    {
        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt($page_name, $page_number)/*page number*/,
            $limit/*limit per page*/
        );
        return $pagination;
    }
}