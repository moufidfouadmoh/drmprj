<?php

namespace App\Manager;


use App\Entity\Diplome;
use App\Repository\DiplomeRepository;

class DiplomeManager implements DiplomeManagerInterface
{
    /**
     * @var DiplomeRepository
     */
    private $diplomeRepository;

    public function __construct(DiplomeRepository $diplomeRepository)
    {
        $this->diplomeRepository = $diplomeRepository;
    }

    public function save($diplome)
    {
        if($diplome instanceof Diplome){
            if(is_null($diplome->getId())){
                $this->diplomeRepository->getEm()->persist($diplome);
            }
            $this->diplomeRepository->getEm()->flush();
            return $diplome;
        }
        return null;
    }

    public function delete($diplome)
    {
        if($diplome instanceof Diplome){
            $this->diplomeRepository->getEm()->remove($diplome);
            $this->diplomeRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getDiplomeByNom($nom)
    {
        $qb = $this->diplomeRepository->selectByNom($nom);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getDiplomeLikeNom($nom)
    {
        $qb = $this->diplomeRepository->selectLikeNom($nom);
        return $qb->getQuery()->getResult();
    }

    public function getRepository()
    {
        return $this->diplomeRepository;
    }
}