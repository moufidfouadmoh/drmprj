<?php

namespace App\Manager;


use App\Entity\Situation;
use App\Repository\SituationRepository;

class SituationManager implements SituationManagerInterface
{
    /**
     * @var SituationRepository
     */
    private $situationRepository;

    public function __construct(SituationRepository $situationRepository)
    {
        $this->situationRepository = $situationRepository;
    }

    public function getRepository()
    {
        return $this->situationRepository;
    }

    public function save($situation)
    {
        if($situation instanceof Situation){
            if(is_null($situation->getId())){
                $this->situationRepository->getEm()->persist($situation);
            }
            $this->situationRepository->getEm()->flush();
            return $situation;
        }
        return null;
    }

    public function delete($situation)
    {
        if($situation instanceof Situation){
            $this->situationRepository->getEm()->remove($situation);
            $this->situationRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getSituationBySlugWithDetail($slug)
    {
        $qb = $this->situationRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}