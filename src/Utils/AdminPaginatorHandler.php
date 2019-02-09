<?php

namespace App\Utils;


use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

abstract class AdminPaginatorHandler
{
    use FormHandlerTrait,PaginatorHandlerTrait;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                PaginatorInterface $paginator)
    {

        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->paginator = $paginator;
    }
    /** @return QueryBuilder */
    public abstract function getQueryBuilderList();

    public function getPaginator(Request $request,int $limit = 10,string $page_name = 'page',int $page_number = 1)
    {
        $qb = $this->getQueryBuilderList();
        return $this->paginate($qb,$request,$page_name,$page_number,$limit);
    }
}