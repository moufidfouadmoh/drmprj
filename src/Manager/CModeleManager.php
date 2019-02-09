<?php

namespace App\Manager;

use App\Entity\CModele;
use App\Repository\CModeleRepository;

class CModeleManager implements CModeleManagerInterface
{
    /**
     * @var CModeleRepository
     */
    private $modeleRepository;

    public function __construct(CModeleRepository $modeleRepository)
    {
        $this->modeleRepository = $modeleRepository;
    }

    public function getRepository()
    {
        return $this->modeleRepository;
    }


    public function save($modele)
    {
        if($modele instanceof CModele){
            if(is_null($modele->getId())){
                $this->modeleRepository->getEm()->persist($modele);
            }
            $this->modeleRepository->getEm()->flush();
            return $modele;
        }
        return null;
    }

    public function delete($modele)
    {
        if($modele instanceof CModele){
            $this->modeleRepository->getEm()->remove($modele);
            $this->modeleRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getCModeleByNom($nom)
    {
        $qb = $this->modeleRepository->selectOneByNom($nom);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getCModelesByNomAndStatuts($etat, $statuts = [])
    {
        $qb = $this->modeleRepository->selectByEtatAndStatuts($etat,$statuts);
        return $qb->getQuery()->getResult();
    }

    public function getCModeleBySlugWithDetail($slug)
    {
        $qb = $this->modeleRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}