<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\AgenceDatatable;
use App\Entity\Agence;
use App\Manager\AgenceManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class AgenceHandler extends AdminDatatableHandler
{
    /**
     * @var Agence
     */
    private $agence;
    /**
     * @var AgenceManagerInterface
     */
    private $agenceManager;


    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                AgenceManagerInterface $agenceManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->agenceManager = $agenceManager;
    }

    /**
     * @return Agence
     */
    public function getAgence(): Agence
    {
        return $this->agence;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(AgenceDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->agenceManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Agence $agence = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($agence)){
                $saved = $this->agenceManager->save($form->getData());
                if(!is_null($saved)){
                    $this->agence = $saved;
                    return true;
                }
            }else{
                return $this->agenceManager->delete($agence);
            }
        }
        return false;
    }
}