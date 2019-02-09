<?php

namespace App\Controller\Native\Admin;

use App\Entity\Article;
use App\Form\Type\ArticleFormType;
use App\Handler\Native\Admin\ArticleHandler;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class ArticleController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/article")
 * @Security("is_granted('ROLE_ADMIN_ARTICLE')",message="exception.authorization")
 */
class ArticleController
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var ArticleHandler
     */
    private $articleHandler;

    public function __construct(Environment $twig,
                                ArticleHandler $articleHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->articleHandler = $articleHandler;
    }

    /**
     * @Route("/index",name="native.admin.article.index")
     */
    public function index(Request $request)
    {
        $handler = $this->articleHandler;
        $datatable = $handler->buildDatatable();

        if ($request->isXmlHttpRequest()) {
            if ($request->isXmlHttpRequest()) {
                /** @var ArticleRepository $repository */
                $repository = $handler->getRepository();
                $qb = $handler->setQueryBuilderList(function () use ($repository){
                    return $repository->selectAll();
                });
                return $handler->buildResponse($datatable,$qb);
            }
        }
        $html = $this->twig->render('Admin/Article/index.html.twig',[
            'datatable' => $datatable
        ]);
        return new Response($html);
    }

    /**
     * @Route("/add",name="native.admin.article.add")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function add(Request $request)
    {
        $article = new Article();
        $form = $this->articleHandler->createForm(ArticleFormType::class,$article);
        $html = $this->twig->render('Admin/Article/add.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->articleHandler->process($form,$request)){
            $article = $this->articleHandler->getArticle();
            $url = $this->articleHandler->generateUrl('native.admin.article.show',[
                'slug' => $article->getSlug()
            ]);
            return new RedirectResponse($url);
        }

        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.article.show")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Request $request)
    {
        $article = $this->articleHandler->getArticleWithDetail($request,'slug');
        $html = $this->twig->render('Admin/Article/show.html.twig', [
            'article' => $article
        ]);
        return new Response($html);
    }


    /**
     * @Route("/{slug}/edit",name="native.admin.article.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request)
    {
        $article = $this->articleHandler->getArticleWithDetail($request,'slug');
        $form = $this->articleHandler->createForm(ArticleFormType::class,$article);
        $html = $this->twig->render('Admin/Article/edit.html.twig',[
            'form' => $form->createView(),
            'edit' => true
        ]);

        if($this->articleHandler->process($form,$request)){
            $article = $this->articleHandler->getArticle();
            return new RedirectResponse($this->articleHandler->generateUrl('native.admin.article.show',[
                'slug' => $article->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.article.delete")
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,Article $article)
    {
        $form = $this->articleHandler->createDeleteForm($article->getId(),'native.admin.article.delete');

        if($this->articleHandler->process($form,$request,$article)){
            $url = $this->articleHandler->generateUrl('native.admin.article.index');
        }else{
            $url = $this->articleHandler->generateUrl('native.admin.article.show',[
                'slug' => $article->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }
}