<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\LieuDatatable;
use App\Entity\Lieu;
use App\Manager\LieuManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class LieuHandler extends AdminDatatableHandler
{
    /**
     * @var Lieu
     */
    private $lieu;
    /**
     * @var LieuManagerInterface
     */
    private $lieuManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router, LieuManagerInterface $lieuManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->lieuManager = $lieuManager;
    }

    /**
     * @return Lieu
     */
    public function getLieu(): Lieu
    {
        return $this->lieu;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(LieuDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->lieuManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Lieu $lieu = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($lieu)){
                $saved = $this->lieuManager->save($form->getData());
                if(!is_null($saved)){
                    $this->lieu = $saved;
                    return true;
                }
            }else{
                return $this->lieuManager->delete($lieu);
            }

        }
        return false;
    }
}