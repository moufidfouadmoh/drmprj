<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\PatrimoineInformatiqueDatatable;
use App\Entity\PatrimoineInformatique;
use App\Event\Subscriber\PatrimoineEvent;
use App\Manager\PatrimoineInformatiqueManagerInterface;
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

class PatrimoineInformatiqueHandler extends AdminDatatableHandler
{
    /**
     * @var PatrimoineInformatique
     */
    private $patrimoine;
    /**
     * @var PatrimoineInformatiqueManagerInterface
     */
    private $patrimoineInformatiqueManager;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                PatrimoineInformatiqueManagerInterface $patrimoineInformatiqueManager,
                                EventDispatcherInterface $dispatcher)
    {
        parent::__construct($datatableFactory,$datatableResponse,$formFactory,$router);
        $this->patrimoineInformatiqueManager = $patrimoineInformatiqueManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return PatrimoineInformatique
     */
    public function getPatrimoine(): PatrimoineInformatique
    {
        return $this->patrimoine;
    }

    /** @return EntityRepository */
    public function getRepository()
    {
        return $this->patrimoineInformatiqueManager->getRepository();
    }

    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(PatrimoineInformatiqueDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }


    public function process(FormInterface $form,Request $request,PatrimoineInformatique $patrimoine = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($patrimoine)){
                $event = new PatrimoineEvent();
                $event->setPatrimoine($form->getData());
                $patrimoine = $this->dispatcher->dispatch(PatrimoineEvent::ON_ADD_PATRIMOINE,$event)->getPatrimoine();
                $saved = $this->patrimoineInformatiqueManager->save($patrimoine);
                if(!is_null($saved)){
                    $this->patrimoine = $saved;
                    return true;
                }
            }else{
                $event = new PatrimoineEvent();
                $event->setPatrimoine($form->getData());
                $patrimoine = $this->dispatcher->dispatch(PatrimoineEvent::ON_DELETE_PATRIMOINE,$event)->getPatrimoine();
                return $this->patrimoineInformatiqueManager->delete($patrimoine);
            }
        }
        return false;
    }

    public function getPatrimoineWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $patrimoine = $this->patrimoineInformatiqueManager->getPatrimoineBySlugWithDetail($slug);
        if(!is_null($patrimoine)){
            return $patrimoine;
        }
        throw  new NotFoundHttpException();
    }
}