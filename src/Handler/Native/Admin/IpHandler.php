<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\IpDatatable;
use App\Entity\Ip;
use App\Manager\IpManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class IpHandler extends AdminDatatableHandler
{
    /**
     * @var Ip
     */
    private $ip;
    /**
     * @var IpManagerInterface
     */
    private $ipManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router, IpManagerInterface $ipManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->ipManager = $ipManager;
    }

    /**
     * @return Ip
     */
    public function getIp(): Ip
    {
        return $this->ip;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(IpDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->ipManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Ip $ip = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($ip)){
                $saved = $this->ipManager->save($form->getData());
                if(!is_null($saved)){
                    $this->ip = $saved;
                    return true;
                }
            }else{
                return $this->ipManager->delete($ip);
            }

        }
        return false;
    }
}