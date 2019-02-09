<?php

namespace App\Manager;


use App\Entity\Etablissement;
use App\Repository\EtablissementRepository;

class EtablissementManager implements EtablissementManagerInterface
{
    /**
     * @var EtablissementRepository
     */
    private $etablissementRepository;

    public function __construct(EtablissementRepository $etablissementRepository)
    {
        $this->etablissementRepository = $etablissementRepository;
    }

    public function save($etablissement)
    {
        if($etablissement instanceof Etablissement){
            if(is_null($etablissement->getId())){
                $this->etablissementRepository->getEm()->persist($etablissement);
            }
            $this->etablissementRepository->getEm()->flush();
            return $etablissement;
        }
        return null;
    }

    public function delete($etablissement)
    {
        if($etablissement instanceof Etablissement){
            $this->etablissementRepository->getEm()->remove($etablissement);
            $this->etablissementRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getEtablissementByNom($nom)
    {
        $qb = $this->etablissementRepository->selectByNom($nom);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getEtablissementLikeNom($nom)
    {
        $qb = $this->etablissementRepository->selectLikeNom($nom);
        return $qb->getQuery()->getResult();
    }

    public function getRepository()
    {
        return $this->etablissementRepository;
    }
}