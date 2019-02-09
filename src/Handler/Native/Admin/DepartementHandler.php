<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\DepartementDatatable;
use App\Entity\Departement;
use App\Manager\DepartementManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class DepartementHandler extends AdminDatatableHandler
{
    /**
     * @var Departement
     */
    private $departement;
    /**
     * @var DepartementManagerInterface
     */
    private $departementManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                DepartementManagerInterface $departementManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->departementManager = $departementManager;
    }

    /**
     * @return Departement
     */
    public function getDepartement(): Departement
    {
        return $this->departement;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(DepartementDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->departementManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Departement $departement = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($departement)){
                $saved = $this->departementManager->save($form->getData());
                if(!is_null($saved)){
                    $this->departement = $saved;
                    return true;
                }
            }else{
                return $this->departementManager->delete($departement);
            }

        }
        return false;
    }

    public function getDepartementWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $departement = $this->departementManager->getDepartementBySlugWithDetail($slug);
        if(!is_null($departement)){
            return $departement;
        }
        throw  new NotFoundHttpException();
    }
}