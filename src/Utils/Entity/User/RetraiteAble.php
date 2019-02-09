<?php

namespace App\Utils\Entity\User;


use App\Utils\Type\Choice\GroupeChoiceType;

trait RetraiteAble
{
    protected $currentgroupe;
    protected $currentniveau;
    protected $currentechelon;
    public abstract function getDatenaissance();

    /**
     * @return mixed
     */
    public function getCurrentechelon()
    {
        return $this->currentechelon;
    }

    /**
     * @return mixed
     */
    public function getCurrentgroupe()
    {
        return $this->currentgroupe;
    }

    /**
     * @return mixed
     */
    public function getCurrentniveau()
    {
        return $this->currentniveau;
    }
    public function getAgeRetraite()
    {
        $ageDeprtRetraite = null;
        $groupe = $this->getCurrentgroupe();
        $niveau = $this->getCurrentniveau();

        if($groupe == GroupeChoiceType::GROUPE_I){
            if($niveau == 1 || $niveau == 2){
                $ageDeprtRetraite = 58;
            }
            if($niveau == 3 || $niveau == 4){
                $ageDeprtRetraite = 60;
            }
        }elseif($groupe == GroupeChoiceType::GROUPE_II){
            $ageDeprtRetraite = 62;
        }elseif($groupe == GroupeChoiceType::GROUPE_III){
            $ageDeprtRetraite = 65;
        }

        return $ageDeprtRetraite;
    }

    public function getDateRetraite(\DateTime $date = null)
    {
        $dateNaissance = !is_null($date) ? clone $date : clone $this->getDatenaissance();
        $ageRetraite = $this->getAgeRetraite();
        if(!is_null($ageRetraite)){
            $dateRetraite = $dateNaissance->add(new \DateInterval('P'.$ageRetraite.'Y'));
            return $dateRetraite;
        }
        return null;
    }
}