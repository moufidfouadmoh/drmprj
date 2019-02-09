<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\MaterielInformatiqueDatatable;
use App\Entity\MaterielInformatique;
use App\Manager\MaterielInformatiqueManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class MaterielInformatiqueHandler extends AdminDatatableHandler
{
    /**
     * @var MaterielInformatique
     */
    private $materiel;
    /**
     * @var MaterielInformatiqueManagerInterface
     */
    private $materielInformatiqueManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                MaterielInformatiqueManagerInterface $materielInformatiqueManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->materielInformatiqueManager = $materielInformatiqueManager;
    }

    /**
     * @return MaterielInformatique
     */
    public function getMateriel(): MaterielInformatique
    {
        return $this->materiel;
    }
    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(MaterielInformatiqueDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->materielInformatiqueManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,MaterielInformatique $materiel = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($materiel)){
                $saved = $this->materielInformatiqueManager->save($form->getData());
                if(!is_null($saved)){
                    $this->materiel = $saved;
                    return true;
                }
            }else{
                return $this->materielInformatiqueManager->delete($materiel);
            }
        }
        return false;
    }

    public function getMaterielBySlugWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $materiel = $this->materielInformatiqueManager->getMaterielBySlugWithDetail($slug);
        if(!is_null($materiel)){
            return $materiel;
        }
        throw  new NotFoundHttpException();
    }
}