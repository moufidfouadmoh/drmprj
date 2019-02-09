<?php

namespace App\Manager;


use App\Entity\Cours;
use App\Repository\CoursRepository;

class CoursManager implements CoursManagerInterface
{
    /**
     * @var CoursRepository
     */
    private $coursRepository;

    public function __construct(CoursRepository $coursRepository)
    {
        $this->coursRepository = $coursRepository;
    }

    public function save($cours)
    {
        if($cours instanceof Cours){
            if(is_null($cours->getId())){
                $this->coursRepository->getEm()->persist($cours);
            }
            $this->coursRepository->getEm()->flush();
            return $cours;
        }
        return null;
    }

    public function delete($cours)
    {
        if($cours instanceof Cours){
            $this->coursRepository->getEm()->remove($cours);
            $this->coursRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getCoursByNom($nom)
    {
        $qb = $this->coursRepository->selectByNom($nom);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getCoursLikeNom($nom)
    {
        $qb = $this->coursRepository->selectLikeNom($nom);
        return $qb->getQuery()->getResult();
    }

    public function getRepository()
    {
        return $this->coursRepository;
    }
}