<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\CModeleDatatable;
use App\Entity\CModele;
use App\Manager\CModeleManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class CModeleHandler extends AdminDatatableHandler
{
    /**
     * @var CModele
     */
    private $modele;
    /**
     * @var CModeleManagerInterface
     */
    private $modeleManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                CModeleManagerInterface $modeleManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->modeleManager = $modeleManager;
    }

    /**
     * @return CModele
     */
    public function getModele(): CModele
    {
        return $this->modele;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(CModeleDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->modeleManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,CModele $modele = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($modele)){
                $saved = $this->modeleManager->save($form->getData());
                if(!is_null($saved)){
                    $this->modele = $saved;
                    return true;
                }
            }else{
                return $this->modeleManager->delete($modele);
            }

        }
        return false;
    }
}