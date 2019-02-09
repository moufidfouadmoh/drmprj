<?php

namespace App\Manager;


use App\Entity\Affectation;
use App\Repository\AffectationRepository;

class AffectationManager implements AffectationManagerInterface
{
    /**
     * @var AffectationRepository
     */
    private $affectationRepository;

    public function __construct(AffectationRepository $affectationRepository)
    {
        $this->affectationRepository = $affectationRepository;
    }

    public function getRepository()
    {
        return $this->affectationRepository;
    }

    public function save($affectation)
    {
        if($affectation instanceof Affectation){
            if(is_null($affectation->getId())){
                $this->affectationRepository->getEm()->persist($affectation);
            }
            $this->affectationRepository->getEm()->flush();
            return $affectation;
        }
        return null;
    }

    public function delete($affectation)
    {
        if($affectation instanceof Affectation){
            $this->affectationRepository->getEm()->remove($affectation);
            $this->affectationRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getAffectationBySlugWithDetail($slug)
    {
        $qb = $this->affectationRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}