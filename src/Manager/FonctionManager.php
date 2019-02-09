<?php

namespace App\Manager;

use App\Entity\Fonction;
use App\Repository\FonctionRepository;

class FonctionManager implements FonctionManagerInterface
{
    /**
     * @var FonctionRepository
     */
    private $fonctionRepository;

    public function __construct(FonctionRepository $fonctionRepository)
    {
        $this->fonctionRepository = $fonctionRepository;
    }

    /**
     * @return FonctionRepository
     */
    public function getRepository()
    {
        return $this->fonctionRepository;
    }

    public function save($fonction)
    {
        if($fonction instanceof Fonction){
            if(is_null($fonction->getId())){
                $this->fonctionRepository->getEm()->persist($fonction);
            }
            $this->fonctionRepository->getEm()->flush();
            return $fonction;
        }
        return null;
    }

    public function delete($fonction)
    {
        if($fonction instanceof Fonction){
            $this->fonctionRepository->getEm()->remove($fonction);
            $this->fonctionRepository->getEm()->flush();
            return true;
        }
        return false;
    }
}