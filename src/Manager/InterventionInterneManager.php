<?php

namespace App\Manager;

use App\Entity\InterventionInterne;
use App\Repository\InterventionInterneRepository;

class InterventionInterneManager implements InterventionInterneManagerInterface
{
    /**
     * @var InterventionInterneRepository
     */
    private $interventionRepository;

    public function __construct(InterventionInterneRepository $interventionRepository)
    {
        $this->interventionRepository = $interventionRepository;
    }

    /**
     * @return InterventionInterneRepository
     */
    public function getRepository()
    {
        return $this->interventionRepository;
    }

    public function save($intervention)
    {
        if($intervention instanceof InterventionInterne){
            if(is_null($intervention->getId())){
                $this->interventionRepository->getEm()->persist($intervention);
            }
            $this->interventionRepository->getEm()->flush();
            return $intervention;
        }
        return null;
    }

    public function delete($intervention)
    {
        if($intervention instanceof InterventionInterne){
            $this->interventionRepository->getEm()->remove($intervention);
            $this->interventionRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getInterventionBySlugWithDetail($slug)
    {
        $qb = $this->interventionRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}