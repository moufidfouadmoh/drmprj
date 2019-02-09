<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\ArticleDatatable;
use App\Datatables\Admin\LieuDatatable;
use App\Entity\Article;
use App\Entity\Lieu;
use App\Event\Subscriber\ArticleEvent;
use App\Manager\ArticleManagerInterface;
use App\Manager\LieuManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class ArticleHandler extends AdminDatatableHandler
{
    /**
     * @var Article
     */
    private $article;
    /**
     * @var ArticleManagerInterface
     */
    private $articleManager;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                ArticleManagerInterface $articleManager,
                                EventDispatcherInterface $dispatcher)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->articleManager = $articleManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(ArticleDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->articleManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Article $article = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($article)){
                $event = new ArticleEvent();
                $event->setArticle($form->getData());
                $article = $this->dispatcher->dispatch(ArticleEvent::ON_ADD_ARTICLE,$event)->getArticle();
                $saved = $this->articleManager->save($article);
                if(!is_null($saved)){
                    $this->article = $saved;
                    return true;
                }
            }else{
                return $this->articleManager->delete($article);
            }
        }
        return false;
    }

    public function getArticleWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $article = $this->articleManager->getArticleBySlugWithDetail($slug);
        if(!is_null($article)){
            return $article;
        }
        throw  new NotFoundHttpException();
    }
}