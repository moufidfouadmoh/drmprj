<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\StatutDatatable;
use App\Entity\Statut;
use App\Manager\StatutManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class StatutHandler extends AdminDatatableHandler
{
    /**
     * @var Statut
     */
    private $statut;
    /**
     * @var StatutManagerInterface
     */
    private $statutManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router, StatutManagerInterface $statutManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->statutManager = $statutManager;
    }

    /**
     * @return Statut
     */
    public function getStatut(): Statut
    {
        return $this->statut;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(StatutDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->statutManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Statut $statut = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($statut)){
                $saved = $this->statutManager->save($form->getData());
                if(!is_null($saved)){
                    $this->statut = $saved;
                    return true;
                }
            }else{
                return $this->statutManager->delete($statut);
            }
        }
        return false;
    }
}