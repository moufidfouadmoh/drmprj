<?php

namespace App\Manager;

use App\Entity\Statut;
use App\Repository\StatutRepository;

class StatutManager implements StatutManagerInterface
{
    /**
     * @var StatutRepository
     */
    private $statutRepository;

    public function __construct(StatutRepository $statutRepository)
    {
        $this->statutRepository = $statutRepository;
    }

    /**
     * @return StatutRepository
     */
    public function getRepository()
    {
        return $this->statutRepository;
    }

    public function save($statut)
    {
        if($statut instanceof Statut){
            if(is_null($statut->getId())){
                $this->statutRepository->getEm()->persist($statut);
            }
            $this->statutRepository->getEm()->flush();
            return $statut;
        }
        return null;
    }

    public function delete($statut)
    {
        if($statut instanceof Statut){
            $this->statutRepository->getEm()->remove($statut);
            $this->statutRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getStatutByNom($nom)
    {
        $qb = $this->statutRepository->selectOneByNom($nom);
        return $qb->getQuery()->getOneOrNullResult();
    }
}