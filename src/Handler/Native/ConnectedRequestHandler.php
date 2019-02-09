<?php

namespace App\Handler\Native;


use App\Manager\ArticleManagerInterface;
use App\Utils\PaginatorHandlerTrait;
use App\Utils\QueryBuilderHandlerTrait;
use Knp\Component\Pager\PaginatorInterface;

class ConnectedRequestHandler implements ConnectedRequestHandlerInterface
{
    use PaginatorHandlerTrait,QueryBuilderHandlerTrait;
    /**
     * @var ArticleManagerInterface
     */
    private $articleManager;

    public function __construct(PaginatorInterface $paginator, ArticleManagerInterface $articleManager)
    {
        $this->paginator = $paginator;
        $this->articleManager = $articleManager;
    }

    public function getArticleReposiotry()
    {
        return $this->articleManager->getRepository();
    }
}