<?php

namespace App\Manager;

use App\Entity\Direction;
use App\Repository\DirectionRepository;

class DirectionManager implements DirectionManagerInterface
{
    /**
     * @var DirectionRepository
     */
    private $directionRepository;

    public function __construct(DirectionRepository $directionRepository)
    {
        $this->directionRepository = $directionRepository;
    }

    public function getRepository()
    {
        return $this->directionRepository;
    }

    public function save($direction)
    {
        if($direction instanceof Direction){
            if(is_null($direction->getId())){
                $this->directionRepository->getEm()->persist($direction);
            }
            $this->directionRepository->getEm()->flush();
            return $direction;
        }
        return null;
    }

    public function delete($direction)
    {
        if($direction instanceof Direction){
            $this->directionRepository->getEm()->remove($direction);
            $this->directionRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getDirectionBySlugWithDetail($slug)
    {
        $qb = $this->directionRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}