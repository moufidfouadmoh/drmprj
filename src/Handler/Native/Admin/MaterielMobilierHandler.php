<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\MaterielMobilierDatatable;
use App\Entity\MaterielMobilier;
use App\Manager\MaterielMobilierManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class MaterielMobilierHandler extends AdminDatatableHandler
{
    /**
     * @var MaterielMobilier
     */
    private $materiel;
    /**
     * @var MaterielMobilierManagerInterface
     */
    private $materielMobilierManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                MaterielMobilierManagerInterface $materielMobilierManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->materielMobilierManager = $materielMobilierManager;
    }

    /**
     * @return MaterielMobilier
     */
    public function getMateriel(): MaterielMobilier
    {
        return $this->materiel;
    }
    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(MaterielMobilierDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->materielMobilierManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,MaterielMobilier $materiel = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($materiel)){
                $saved = $this->materielMobilierManager->save($form->getData());
                if(!is_null($saved)){
                    $this->materiel = $saved;
                    return true;
                }
            }else{
                return $this->materielMobilierManager->delete($materiel);
            }
        }
        return false;
    }

    public function getMaterielBySlugWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $materiel = $this->materielMobilierManager->getMaterielBySlugWithDetail($slug);
        if(!is_null($materiel)){
            return $materiel;
        }
        throw  new NotFoundHttpException();
    }
}