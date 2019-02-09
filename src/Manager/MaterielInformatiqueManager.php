<?php

namespace App\Manager;

use App\Entity\MaterielInformatique;
use App\Repository\MaterielInformatiqueRepository;

class MaterielInformatiqueManager implements MaterielInformatiqueManagerInterface
{
    /**
     * @var MaterielInformatiqueRepository
     */
    private $informatiqueRepository;

    public function __construct(MaterielInformatiqueRepository $informatiqueRepository)
    {
        $this->informatiqueRepository = $informatiqueRepository;
    }

    /**
     * @return MaterielInformatiqueRepository
     */
    private function getInformatiqueRepository(): MaterielInformatiqueRepository
    {
        return $this->informatiqueRepository;
    }

    public function getRepository()
    {
        return $this->getInformatiqueRepository();
    }


    public function save($materiel)
    {
        if($materiel instanceof MaterielInformatique){
            if(is_null($materiel->getId())){
                $this->informatiqueRepository->getEm()->persist($materiel);
            }
            $this->informatiqueRepository->getEm()->flush();
            return $materiel;
        }
        return null;
    }

    public function delete($materiel)
    {
        if($materiel instanceof MaterielInformatique){
            $this->informatiqueRepository->getEm()->remove($materiel);
            $this->informatiqueRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getMaterielByEquipementAndMarque($equipement, $marque)
    {
        $qb = $this->informatiqueRepository->selectOneByEquipementAndMarque($equipement,$marque);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getMaterielBySlugWithDetail($slug)
    {
        $qb = $this->informatiqueRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}