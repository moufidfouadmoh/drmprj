<?php

namespace App\Manager;

use App\Entity\PatrimoineInformatique;
use App\Repository\PatrimoineInformatiqueRepository;

class PatrimoineInformatiqueManager implements PatrimoineInformatiqueManagerInterface
{
    /**
     * @var PatrimoineInformatiqueRepository
     */
    private $informatiqueRepository;

    public function __construct(PatrimoineInformatiqueRepository $informatiqueRepository)
    {
        $this->informatiqueRepository = $informatiqueRepository;
    }

    /**
     * @return PatrimoineInformatiqueRepository
     */
    private function getInformatiqueRepository(): PatrimoineInformatiqueRepository
    {
        return $this->informatiqueRepository;
    }

    public function getRepository()
    {
        return $this->getInformatiqueRepository();
    }


    public function save($patrimoine)
    {
        if($patrimoine instanceof PatrimoineInformatique){
            if(is_null($patrimoine->getId())){
                $this->informatiqueRepository->getEm()->persist($patrimoine);
            }
            $this->informatiqueRepository->getEm()->flush();
            return $patrimoine;
        }
        return null;
    }

    public function delete($patrimoine)
    {
        if($patrimoine instanceof PatrimoineInformatique){
            $this->informatiqueRepository->getEm()->remove($patrimoine);
            $this->informatiqueRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getPatrimoineBySlugWithDetail($slug)
    {
        $qb = $this->informatiqueRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}