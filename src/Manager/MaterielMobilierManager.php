<?php

namespace App\Manager;

use App\Entity\MaterielMobilier;
use App\Repository\MaterielMobilierRepository;

class MaterielMobilierManager implements MaterielMobilierManagerInterface
{
    /**
     * @var MaterielMobilierRepository
     */
    private $mobilierRepository;

    public function __construct(MaterielMobilierRepository $mobilierRepository)
    {
        $this->mobilierRepository = $mobilierRepository;
    }

    /**
     * @return MaterielMobilierRepository
     */
    private function getMobilierRepository(): MaterielMobilierRepository
    {
        return $this->mobilierRepository;
    }

    public function getRepository()
    {
        return $this->getMobilierRepository();
    }


    public function save($materiel)
    {
        if($materiel instanceof MaterielMobilier){
            if(is_null($materiel->getId())){
                $this->mobilierRepository->getEm()->persist($materiel);
            }
            $this->mobilierRepository->getEm()->flush();
            return $materiel;
        }
        return null;
    }

    public function delete($materiel)
    {
        if($materiel instanceof MaterielMobilier){
            $this->mobilierRepository->getEm()->remove($materiel);
            $this->mobilierRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getMaterielBySlugWithDetail($slug)
    {
        $qb = $this->mobilierRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}