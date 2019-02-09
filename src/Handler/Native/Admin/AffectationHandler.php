<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\AffectationDatatable;
use App\Entity\Affectation;
use App\Event\Subscriber\AffectationEvent;
use App\Manager\AffectationManager;
use App\Utils\AdminDatatableHandler;
use Doctrine\ORM\QueryBuilder;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class AffectationHandler extends AdminDatatableHandler
{
    /**
     * @var Affectation
     */
    private $affectation;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var AffectationManager
     */
    private $affectationManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                AffectationManager $affectationManager,
                                EventDispatcherInterface $dispatcher)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->dispatcher = $dispatcher;
        $this->affectationManager = $affectationManager;
    }

    /**
     * @return Affectation
     */
    public function getAffectation(): Affectation
    {
        return $this->affectation;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(AffectationDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->affectationManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Affectation $affectation = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($affectation)){
                $data = $form->getData();
                $event = new AffectationEvent($data);
                $this->dispatcher->dispatch(AffectationEvent::ON_ADD_AFFECTATION,$event);
                $saved = $this->affectationManager->save($form->getData());
                if(!is_null($saved)){
                    $this->affectation = $saved;
                    return true;
                }
            }else{
                return $this->affectationManager->delete($affectation);
            }

        }
        return false;
    }

    public function getAffectationWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $affectation = $this->affectationManager->getAffectationBySlugWithDetail($slug);
        if(!is_null($affectation)){
            return $affectation;
        }
        throw  new NotFoundHttpException();
    }
}