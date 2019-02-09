<?php

namespace App\Manager;


use App\Entity\Departement;
use App\Repository\DepartementRepository;

class DepartementManager implements DepartementManagerInterface
{
    /**
     * @var DepartementRepository
     */
    private $departementRepository;

    public function __construct(DepartementRepository $departementRepository)
    {
        $this->departementRepository = $departementRepository;
    }

    public function getRepository()
    {
        return $this->departementRepository;
    }

    public function save($departement)
    {
        if($departement instanceof Departement){
            if(is_null($departement->getId())){
                $this->departementRepository->getEm()->persist($departement);
            }
            $this->departementRepository->getEm()->flush();
            return $departement;
        }
        return null;
    }

    public function delete($departement)
    {
        if($departement instanceof Departement){
            $this->departementRepository->getEm()->remove($departement);
            $this->departementRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getDepartementBySlugWithDetail($slug)
    {
        $qb = $this->departementRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}