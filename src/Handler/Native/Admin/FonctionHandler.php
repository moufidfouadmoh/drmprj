<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\FonctionDatatable;
use App\Entity\Fonction;
use App\Manager\FonctionManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class FonctionHandler extends AdminDatatableHandler
{
    /**
     * @var Fonction
     */
    private $fonction;
    /**
     * @var FonctionManagerInterface
     */
    private $fonctionManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router, FonctionManagerInterface $fonctionManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->fonctionManager = $fonctionManager;
    }

    /**
     * @return Fonction
     */
    public function getFonction(): Fonction
    {
        return $this->fonction;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(FonctionDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->fonctionManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Fonction $fonction = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($fonction)){
                $saved = $this->fonctionManager->save($form->getData());
                if(!is_null($saved)){
                    $this->fonction = $saved;
                    return true;
                }
            }else{
                return $this->fonctionManager->delete($fonction);
            }
        }
        return false;
    }
}