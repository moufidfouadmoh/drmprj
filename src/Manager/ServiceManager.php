<?php

namespace App\Manager;


use App\Entity\Service;
use App\Repository\ServiceRepository;

class ServiceManager implements ServiceManagerInterface
{
    /**
     * @var ServiceRepository
     */
    private $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function getRepository()
    {
        return $this->serviceRepository;
    }

    public function save($service)
    {
        if($service instanceof Service){
            if(is_null($service->getId())){
                $this->serviceRepository->getEm()->persist($service);
            }
            $this->serviceRepository->getEm()->flush();
            return $service;
        }
        return null;
    }

    public function delete($service)
    {
        if($service instanceof Service){
            $this->serviceRepository->getEm()->remove($service);
            $this->serviceRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getServiceBySlugWithDetail($slug)
    {
        $qb = $this->serviceRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}