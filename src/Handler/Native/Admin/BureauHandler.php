<?php

namespace App\Handler\Native\Admin;


use App\Datatables\Admin\BureauDatatable;
use App\Repository\BureauRepository;
use Doctrine\ORM\QueryBuilder;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sg\DatatablesBundle\Response\DatatableResponse;

class BureauHandler
{
    /**
     * @var DatatableFactory
     */
    private $datatableFactory;
    /**
     * @var DatatableResponse
     */
    private $datatableResponse;
    /**
     * @var BureauRepository
     */
    private $bureauRepository;

    public function __construct(DatatableFactory $datatableFactory, DatatableResponse $datatableResponse, BureauRepository $bureauRepository)
    {
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->bureauRepository = $bureauRepository;
    }

    /**
     * @return DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(BureauDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    /**
     * @param DatatableInterface $datatable
     * @param $q
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function buildResponse(DatatableInterface $datatable,$q)
    {
        $responseService = $this->datatableResponse;
        $responseService->setDatatable($datatable);
        if($q instanceof QueryBuilder){
            $responseService->getDatatableQueryBuilder()->setQb($q);
        }elseif (is_string($q)){
            $responseService->getDatatableQueryBuilder()->setQb($this->getBureauRepository()->createQueryBuilder($q));
        }
        return $responseService->getResponse();
    }

    /**
     * @return BureauRepository
     */
    public function getBureauRepository(): BureauRepository
    {
        return $this->bureauRepository;
    }
}