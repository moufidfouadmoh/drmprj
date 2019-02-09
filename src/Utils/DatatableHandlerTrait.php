<?php

namespace App\Utils;


use Doctrine\ORM\QueryBuilder;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sg\DatatablesBundle\Response\DatatableResponse;

trait DatatableHandlerTrait
{
    use QueryBuilderHandlerTrait;
    /**
     * @var DatatableFactory
     */
    protected $datatableFactory;
    /**
     * @var DatatableResponse
     */
    protected $datatableResponse;

    public abstract function buildDatatable();

    public abstract function buildResponse(DatatableInterface $datatable,$q);
}