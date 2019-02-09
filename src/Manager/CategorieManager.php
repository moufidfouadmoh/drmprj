<?php

namespace App\Manager;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;

class CategorieManager implements CategorieManagerInterface
{
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * @return $categorieRepository
     */
    public function getRepository()
    {
        return $this->categorieRepository;
    }

    public function save($categorie)
    {
        if($categorie instanceof Categorie){
            if(is_null($categorie->getId())){
                $this->categorieRepository->getEm()->persist($categorie);
            }
            $this->categorieRepository->getEm()->flush();
            return $categorie;
        }
        return null;
    }

    public function delete($categorie)
    {
        if($categorie instanceof Categorie){
            $this->categorieRepository->getEm()->remove($categorie);
            $this->categorieRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getCategorieByNom($nom)
    {
        $qb = $this->categorieRepository->selectOneByNom($nom);
        return $qb->getQuery()->getOneOrNullResult();
    }
}