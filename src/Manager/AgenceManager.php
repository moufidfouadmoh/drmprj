<?php

namespace App\Manager;

use App\Entity\Agence;
use App\Repository\AgenceRepository;

class AgenceManager implements AgenceManagerInterface
{
    /**
     * @var AgenceRepository
     */
    private $agenceRepository;

    public function __construct(AgenceRepository $agenceRepository)
    {
        $this->agenceRepository = $agenceRepository;
    }

    /**
     * @return AgenceRepository
     */
    public function getRepository()
    {
        return $this->agenceRepository;
    }

    public function save($agence)
    {
        if($agence instanceof Agence){
            if(is_null($agence->getId())){
                $this->agenceRepository->getEm()->persist($agence);
            }
            $this->agenceRepository->getEm()->flush();
            return $agence;
        }
        return null;
    }

    public function delete($agence)
    {
        if($agence instanceof Agence){
            $this->agenceRepository->getEm()->remove($agence);
            $this->agenceRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getAgenceByNom($nom)
    {
        $qb = $this->agenceRepository->selectOneByNom($nom);
        return $qb->getQuery()->getOneOrNullResult();
    }
}