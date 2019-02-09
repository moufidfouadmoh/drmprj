<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\InterventionInterneDatatable;
use App\Entity\InterventionInterne;
use App\Event\Subscriber\InterventionEvent;
use App\Manager\InterventionInterneManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class InterventionInterneHandler extends AdminDatatableHandler
{
    /**
     * @var InterventionInterne
     */
    private $intervention;
    /**
     * @var InterventionInterneManagerInterface
     */
    private $interventionInterneManager;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                InterventionInterneManagerInterface $interventionInterneManager,
                                EventDispatcherInterface $dispatcher)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->interventionInterneManager = $interventionInterneManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return InterventionInterne
     */
    public function getIntervention(): InterventionInterne
    {
        return $this->intervention;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(InterventionInterneDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->interventionInterneManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,InterventionInterne $intervention = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($intervention)){
                $event = new InterventionEvent();
                $event->setIntervention($form->getData());
                $intervention = $this->dispatcher->dispatch(InterventionEvent::ON_ADD_INTERVENTION,$event)->getIntervention();
                $saved = $this->interventionInterneManager->save($intervention);
                if(!is_null($saved)){
                    $this->intervention = $saved;
                    return true;
                }
            }else{
                return $this->interventionInterneManager->delete($intervention);
            }
        }
        return false;
    }

    public function getInterventionBySlugWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $intervention = $this->interventionInterneManager->getInterventionBySlugWithDetail($slug);
        if(!is_null($intervention)){
            return $intervention;
        }
        throw  new NotFoundHttpException();
    }
}