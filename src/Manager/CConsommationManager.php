<?php

namespace App\Manager;

use App\Entity\CConsommation;
use App\Repository\CConsommationRepository;

class CConsommationManager implements CConsommationManagerInterface
{
    /**
     * @var CConsommationRepository
     */
    private $cConsommationRepository;

    public function __construct(CConsommationRepository $cConsommationRepository)
    {
        $this->cConsommationRepository = $cConsommationRepository;
    }

    public function getRepository()
    {
        return $this->cConsommationRepository;
    }

    public function save($consommation)
    {
        if($consommation instanceof CConsommation){
            if(is_null($consommation->getId())){
                $this->cConsommationRepository->getEm()->persist($consommation);
            }
            $this->cConsommationRepository->getEm()->flush();
            return $consommation;
        }
        return null;
    }

    public function delete($consommation)
    {
        if($consommation instanceof CConsommation){
            $this->cConsommationRepository->getEm()->remove($consommation);
            $this->cConsommationRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getCConsommationBySlugWithDetail($slug)
    {
        $qb = $this->cConsommationRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}