<?php

namespace App\Manager;


use App\Entity\Cadrage;
use App\Repository\CadrageRepository;

class CadrageManager implements CadrageManagerInterface
{

    /**
     * @var CadrageRepository
     */
    private $cadrageRepository;

    public function __construct(CadrageRepository $cadrageRepository)
    {
        $this->cadrageRepository = $cadrageRepository;
    }

    public function getRepository()
    {
        return $this->cadrageRepository;
    }

    public function save($cadrage)
    {
        if($cadrage instanceof Cadrage){
            if(is_null($cadrage->getId())){
                $this->cadrageRepository->getEm()->persist($cadrage);
            }
            $this->cadrageRepository->getEm()->flush();
            return $cadrage;
        }
        return null;
    }

    public function delete($cadrage)
    {
        if($cadrage instanceof Cadrage){
            $this->cadrageRepository->getEm()->remove($cadrage);
            $this->cadrageRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getCadrageBySlugWithDetail($slug)
    {
        $qb = $this->cadrageRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}