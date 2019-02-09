<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\PatrimoineMobilierDatatable;
use App\Entity\PatrimoineMobilier;
use App\Event\Subscriber\PatrimoineEvent;
use App\Manager\PatrimoineMobilierManagerInterface;
use App\Repository\PatrimoineMobilierRepository;
use App\Utils\AdminDatatableHandler;
use Doctrine\ORM\EntityRepository;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class PatrimoineMobilierHandler extends AdminDatatableHandler
{
    /**
     * @var PatrimoineMobilier
     */
    private $patrimoine;
    /**
     * @var PatrimoineMobilierRepository
     */
    private $patrimoineMobilierManager;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;


    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                PatrimoineMobilierManagerInterface $patrimoineMobilierManager,
                                EventDispatcherInterface $dispatcher)
    {
        parent::__construct($datatableFactory,$datatableResponse,$formFactory,$router);
        $this->patrimoineMobilierManager = $patrimoineMobilierManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return PatrimoineMobilier
     */
    public function getPatrimoine(): PatrimoineMobilier
    {
        return $this->patrimoine;
    }

    /** @return EntityRepository */
    public function getRepository()
    {
        return $this->patrimoineMobilierManager->getRepository();
    }

    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(PatrimoineMobilierDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }


    public function process(FormInterface $form,Request $request,PatrimoineMobilier $patrimoine = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($patrimoine)){
                $event = new PatrimoineEvent();
                $event->setPatrimoine($form->getData());
                $patrimoine = $this->dispatcher->dispatch(PatrimoineEvent::ON_ADD_PATRIMOINE,$event)->getPatrimoine();
                //dump($patrimoine);die();
                $saved = $this->patrimoineMobilierManager->save($patrimoine);
                if(!is_null($saved)){
                    $this->patrimoine = $saved;
                    return true;
                }
            }else{
                $event = new PatrimoineEvent();
                $event->setPatrimoine($form->getData());
                $patrimoine = $this->dispatcher->dispatch(PatrimoineEvent::ON_DELETE_PATRIMOINE,$event)->getPatrimoine();
                return $this->patrimoineMobilierManager->delete($patrimoine);
            }
        }
        return false;
    }

    public function getPatrimoineWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $patrimoine = $this->patrimoineMobilierManager->getPatrimoineBySlugWithDetail($slug);
        if(!is_null($patrimoine)){
            return $patrimoine;
        }
        throw  new NotFoundHttpException();
    }
}