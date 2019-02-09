<?php

namespace App\Manager;

use App\Entity\PatrimoineMobilier;
use App\Repository\PatrimoineMobilierRepository;

class PatrimoineMobilierManager implements PatrimoineMobilierManagerInterface
{
    /**
     * @var PatrimoineMobilierRepository
     */
    private $mobilierRepository;

    public function __construct(PatrimoineMobilierRepository $mobilierRepository)
    {
        $this->mobilierRepository = $mobilierRepository;
    }

    /**
     * @return PatrimoineMobilierRepository
     */
    private function getMobilierRepository(): PatrimoineMobilierRepository
    {
        return $this->mobilierRepository;
    }

    public function getRepository()
    {
        return $this->getMobilierRepository();
    }


    public function save($patrimoine)
    {
        if($patrimoine instanceof PatrimoineMobilier){
            if(is_null($patrimoine->getId())){
                $this->mobilierRepository->getEm()->persist($patrimoine);
            }
            $this->mobilierRepository->getEm()->flush();
            return $patrimoine;
        }
        return null;
    }

    public function delete($patrimoine)
    {
        if($patrimoine instanceof PatrimoineMobilier){
            $this->mobilierRepository->getEm()->remove($patrimoine);
            $this->mobilierRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getPatrimoineBySlugWithDetail($slug)
    {
        $qb = $this->mobilierRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}