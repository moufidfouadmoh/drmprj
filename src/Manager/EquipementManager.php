<?php

namespace App\Manager;

use App\Entity\Equipement;
use App\Repository\EquipementRepository;

class EquipementManager implements EquipementManagerInterface
{
    /**
     * @var EquipementRepository
     */
    private $equipementRepository;

    public function __construct(EquipementRepository $equipementRepository)
    {
        $this->equipementRepository = $equipementRepository;
    }

    public function save($equipement)
    {
        if($equipement instanceof Equipement){
            if(is_null($equipement->getId())){
                $this->equipementRepository->getEm()->persist($equipement);
            }
            $this->equipementRepository->getEm()->flush();
            return $equipement;
        }
        return null;
    }

    public function delete($equipement)
    {
        if($equipement instanceof Equipement){
            $this->equipementRepository->getEm()->remove($equipement);
            $this->equipementRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getEquipementByNom($nom)
    {
        $qb = $this->equipementRepository->selectByNom($nom);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getEquipementLikeNom($nom)
    {
        $qb = $this->equipementRepository->selectLikeNom($nom);
        return $qb->getQuery()->getResult();
    }

    public function getRepository()
    {
        return $this->equipementRepository;
    }
}