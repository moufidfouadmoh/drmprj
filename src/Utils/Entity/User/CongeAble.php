<?php

namespace App\Utils\Entity\User;


use App\Entity\CConsommation;
use App\Entity\Conge;
use App\Utils\Type\Choice\ModeleChoiceType;
use Doctrine\Common\Collections\ArrayCollection;

trait CongeAble
{
    /** @var ArrayCollection */
    protected $conges;
    public function getCongesValides()
    {
        return $this->conges->filter(function(Conge $conge){
            return $conge instanceof CConsommation && $conge->getDatefin($conge->getDelaiaccorde()) > new \DateTime();
        });
    }

    public function getConsommationCounter()
    {
        $conges = $this->conges->filter(function (Conge $conge){
            return $conge instanceof CConsommation && $conge->getCmodele()->getType() == ModeleChoiceType::ADMINISTRATIF;
        })->toArray();

        $result = array();

        foreach ($conges as $conge){
            /** @var \DateInterval $delai */
            $delai = $conge->getDelaiaccorde();
            if(key_exists($conge->getAnnee(),$result)){
                $result[$conge->getAnnee()] = $result[$conge->getAnnee()] + $delai->d;
            }else{
                $result[$conge->getAnnee()] = $delai->d;
            }
        }

        return $result;
    }

    public function getConsommationByYears(array $years)
    {
        return $this->conges->filter(function (Conge $conge) use ($years){
            $year = $conge->getDatedebut()->format('Y');
            return  $conge instanceof CConsommation && in_array($year,$years);
        });
    }
}