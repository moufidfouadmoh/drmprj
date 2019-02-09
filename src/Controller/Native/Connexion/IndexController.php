<?php

namespace App\Controller\Native\Connexion;

use App\Handler\Native\ConnectedRequestHandler;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class IndexController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var ConnectedRequestHandlerInterface
     */
    private $requestHandler;

    public function __construct(Environment $twig,ConnectedRequestHandler $requestHandler)
    {
        $this->twig = $twig;
        $this->requestHandler = $requestHandler;
    }

    /**
     * @Route("/",name="native.index")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(Request $request)
    {
        /** @var ArticleRepository $repository */
        $repository = $this->requestHandler->getArticleReposiotry();
        $qb = $this->requestHandler->setQueryBuilderList(function () use ($repository){
            return $repository->selectAll();
        });
        $pagination = $this->requestHandler->paginate($qb,$request,10);
        $html = $this->twig->render('Connexion/index.html.twig',[
            'pagination' => $pagination
        ]);
        return new Response($html);
    }
}