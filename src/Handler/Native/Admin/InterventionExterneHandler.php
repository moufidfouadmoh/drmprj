<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\InterventionExterneDatatable;
use App\Entity\InterventionExterne;
use App\Manager\InterventionExterneManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class InterventionExterneHandler extends AdminDatatableHandler
{
    /**
     * @var InterventionExterne
     */
    private $intervention;
    /**
     * @var InterventionExterneManagerInterface
     */
    private $interventionExterneManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                InterventionExterneManagerInterface $interventionExterneManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->interventionExterneManager = $interventionExterneManager;
    }

    /**
     * @return InterventionExterne
     */
    public function getIntervention(): InterventionExterne
    {
        return $this->intervention;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(InterventionExterneDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->interventionExterneManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,InterventionExterne $intervention = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($intervention)){
                $saved = $this->interventionExterneManager->save($form->getData());
                if(!is_null($saved)){
                    $this->intervention = $saved;
                    return true;
                }
            }else{
                return $this->interventionExterneManager->delete($intervention);
            }
        }
        return false;
    }

    public function getInterventionBySlugWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $intervention = $this->interventionExterneManager->getInterventionBySlugWithDetail($slug);
        if(!is_null($intervention)){
            return $intervention;
        }
        throw  new NotFoundHttpException();
    }
}