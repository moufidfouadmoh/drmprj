<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\CadrageDatatable;
use App\Entity\Cadrage;
use App\Event\Subscriber\CadrageEvent;
use App\Manager\CadrageManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class CadrageHandler extends AdminDatatableHandler
{
    /**
     * @var Cadrage
     */
    private $cadrage;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var CadrageManagerInterface
     */
    private $cadrageManager;


    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                CadrageManagerInterface $cadrageManager,
                                EventDispatcherInterface $dispatcher)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->dispatcher = $dispatcher;
        $this->cadrageManager = $cadrageManager;
    }

    /**
     * @return Cadrage
     */
    public function getCadrage(): Cadrage
    {
        return $this->cadrage;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(CadrageDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->cadrageManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Cadrage $cadrage = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($cadrage)){
                $data = $form->getData();
                $event = new CadrageEvent($data);
                $this->dispatcher->dispatch(CadrageEvent::ON_ADD_CADRAGE,$event);
                $saved = $this->cadrageManager->save($form->getData());
                if(!is_null($saved)){
                    $this->cadrage = $saved;
                    return true;
                }
            }else{
                return $this->cadrageManager->delete($cadrage);
            }

        }
        return false;
    }


    public function getCadrageWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $cadrage = $this->cadrageManager->getCadrageBySlugWithDetail($slug);
        if(!is_null($cadrage)){
            return $cadrage;
        }
       throw  new NotFoundHttpException();
    }
}