<?php

namespace App\Manager;

use App\Entity\Marque;
use App\Repository\MarqueRepository;

class MarqueManager implements MarqueManagerInterface
{
    /**
     * @var MarqueRepository
     */
    private $marqueRepository;

    public function __construct(MarqueRepository $marqueRepository)
    {
        $this->marqueRepository = $marqueRepository;
    }

    public function save($marque)
    {
        if($marque instanceof Marque){
            if(is_null($marque->getId())){
                $this->marqueRepository->getEm()->persist($marque);
            }
            $this->marqueRepository->getEm()->flush();
            return $marque;
        }
        return null;
    }

    public function delete($marque)
    {
        if($marque instanceof Marque){
            $this->marqueRepository->getEm()->remove($marque);
            $this->marqueRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getMarqueByNom($nom)
    {
        $qb = $this->marqueRepository->selectByNom($nom);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getMarqueLikeNom($nom)
    {
        $qb = $this->marqueRepository->selectLikeNom($nom);
        return $qb->getQuery()->getResult();
    }

    public function getRepository()
    {
        return $this->marqueRepository;
    }
}