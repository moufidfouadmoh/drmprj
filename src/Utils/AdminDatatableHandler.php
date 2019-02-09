<?php

namespace App\Utils;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;

abstract class AdminDatatableHandler
{
    use DatatableHandlerTrait,FormHandlerTrait;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router)
    {
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /** @return EntityRepository */
    public abstract function getRepository();

    /**
     * @param DatatableInterface $datatable
     * @param QueryBuilder $queryBuilder
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function buildResponse(DatatableInterface $datatable,QueryBuilder $queryBuilder)
    {
        /** @var DatatableResponse $responseService */
        $responseService = $this->datatableResponse;
        $responseService->setDatatable($datatable);
        $responseService->getDatatableQueryBuilder()->setQb($queryBuilder);

        return $responseService->getResponse();
    }
}