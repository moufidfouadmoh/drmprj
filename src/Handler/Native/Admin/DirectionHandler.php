<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\DirectionDatatable;
use App\Entity\Direction;
use App\Manager\DirectionManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class DirectionHandler extends AdminDatatableHandler
{
    /**
     * @var Direction
     */
    private $direction;
    /**
     * @var DirectionManagerInterface
     */
    private $directionManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                DirectionManagerInterface $directionManager)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->directionManager = $directionManager;
    }

    /**
     * @return Direction
     */
    public function getDirection(): Direction
    {
        return $this->direction;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(DirectionDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->directionManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Direction $direction = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($direction)){
                $saved = $this->directionManager->save($form->getData());
                if(!is_null($saved)){
                    $this->direction = $saved;
                    return true;
                }
            }else{
                return $this->directionManager->delete($direction);
            }

        }
        return false;
    }

    public function getDirectionWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $direction = $this->directionManager->getDirectionBySlugWithDetail($slug);
        if(!is_null($direction)){
            return $direction;
        }
        throw  new NotFoundHttpException();
    }
}