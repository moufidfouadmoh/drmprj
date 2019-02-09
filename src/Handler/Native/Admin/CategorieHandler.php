<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\CategorieDatatable;
use App\Entity\Categorie;
use App\Manager\CategorieManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class CategorieHandler extends AdminDatatableHandler
{
    /**
     * @var Categorie
     */
    private $categorie;
    /**
     * @var CategorieManagerInterface
     */
    private $categorieManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                CategorieManagerInterface $categorieManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->categorieManager = $categorieManager;
    }

    /**
     * @return Categorie
     */
    public function getCategorie(): Categorie
    {
        return $this->categorie;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(CategorieDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->categorieManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Categorie $categorie = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($categorie)){
                $saved = $this->categorieManager->save($form->getData());
                if(!is_null($saved)){
                    $this->categorie = $saved;
                    return true;
                }
            }else{
                return $this->categorieManager->delete($categorie);
            }
        }
        return false;
    }
}