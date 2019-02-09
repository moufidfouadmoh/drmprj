<?php

namespace App\Manager;

use App\Entity\Lieu;
use App\Repository\LieuRepository;

class LieuManager implements LieuManagerInterface
{
    /**
     * @var LieuRepository
     */
    private $lieuRepository;

    public function __construct(LieuRepository $lieuRepository)
    {
        $this->lieuRepository = $lieuRepository;
    }

    /**
     * @return LieuRepository
     */
    public function getRepository()
    {
        return $this->lieuRepository;
    }

    public function save($lieu)
    {
        if($lieu instanceof Lieu){
            if(is_null($lieu->getId())){
                $this->lieuRepository->getEm()->persist($lieu);
            }
            $this->lieuRepository->getEm()->flush();
            return $lieu;
        }
        return null;
    }

    public function delete($lieu)
    {
        if($lieu instanceof Lieu){
            $this->lieuRepository->getEm()->remove($lieu);
            $this->lieuRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getLieuByNom($nom)
    {
        $qb = $this->lieuRepository->selectOneByNom($nom);
        return $qb->getQuery()->getOneOrNullResult();
    }
}