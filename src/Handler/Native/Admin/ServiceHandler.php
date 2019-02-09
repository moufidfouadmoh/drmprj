<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\ServiceDatatable;
use App\Entity\Service;
use App\Manager\ServiceManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class ServiceHandler extends AdminDatatableHandler
{
    /**
     * @var Service
     */
    private $service;
    /**
     * @var ServiceManagerInterface
     */
    private $serviceManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                ServiceManagerInterface $serviceManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return Service
     */
    public function getService(): Service
    {
        return $this->service;
    }
    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(ServiceDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->serviceManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Service $service = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($service)){
                $saved = $this->serviceManager->save($form->getData());
                if(!is_null($saved)){
                    $this->service = $saved;
                    return true;
                }
            }else{
                return $this->serviceManager->delete($service);
            }

        }
        return false;
    }

    public function getDepartementWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $service = $this->serviceManager->getServiceBySlugWithDetail($slug);
        if(!is_null($service)){
            return $service;
        }
        throw  new NotFoundHttpException();
    }
}