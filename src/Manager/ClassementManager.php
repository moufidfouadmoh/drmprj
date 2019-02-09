<?php

namespace App\Manager;

use App\Entity\Classement;
use App\Repository\ClassementRepository;

class ClassementManager implements ClassementManagerInterface
{

    /**
     * @var ClassementRepository
     */
    private $classementRepository;

    public function __construct(ClassementRepository $classementRepository)
    {
        $this->classementRepository = $classementRepository;
    }

    public function getRepository()
    {
        return $this->classementRepository;
    }

    public function save($classement)
    {
        if($classement instanceof Classement){
            if(is_null($classement->getId())){
                $this->classementRepository->getEm()->persist($classement);
            }
            $this->classementRepository->getEm()->flush();
            return $classement;
        }
        return null;
    }

    public function delete($classement)
    {
        if($classement instanceof Classement){
            $this->classementRepository->getEm()->remove($classement);
            $this->classementRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getClassementBySlugWithDetail($slug)
    {
        $qb = $this->classementRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}