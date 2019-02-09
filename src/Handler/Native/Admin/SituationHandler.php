<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\SituationDatatable;
use App\Entity\Situation;
use App\Event\Subscriber\SituationEvent;
use App\Manager\SituationManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class SituationHandler extends AdminDatatableHandler
{
    /**
     * @var Situation
     */
    private $situation;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var SituationManagerInterface
     */
    private $situationManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                SituationManagerInterface $situationManager,
                                EventDispatcherInterface $dispatcher)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->dispatcher = $dispatcher;
        $this->situationManager = $situationManager;
    }

    /**
     * @return Situation
     */
    public function getSituation(): Situation
    {
        return $this->situation;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(SituationDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->situationManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Situation $situation = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($situation)){
                $data = $form->getData();
                $event = new SituationEvent($data);
                $this->dispatcher->dispatch(SituationEvent::ON_ADD_SITUATION,$event);
                $saved = $this->situationManager->save($form->getData());
                if(!is_null($saved)){
                    $this->situation = $saved;
                    return true;
                }
            }else{
                return $this->situationManager->delete($situation);
            }

        }
        return false;
    }

    public function getSituationWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $situation = $this->situationManager->getSituationBySlugWithDetail($slug);
        if(!is_null($situation)){
            return $situation;
        }
        throw  new NotFoundHttpException();
    }
}