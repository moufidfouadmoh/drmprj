<?php

namespace App\Utils\Entity\User;


use App\Entity\Situation;
use App\Utils\Type\Choice\SituationChoiceType;
use Doctrine\Common\Collections\ArrayCollection;

trait RecrutementAble
{
    /** @var ArrayCollection */
    protected $situations;

    /**
     * @return ArrayCollection
     */
    public function getSituations(): ArrayCollection
    {
        return $this->situations;
    }
    public function getPremierRecrutement()
    {
        /** @var Situation $premier_recru */
        $premier_recru = $this->getSituations()->filter(function (Situation $situation){
            return $situation->getType() == SituationChoiceType::PREMIER_RECRU;
        })->last();

        return $premier_recru;
    }

    public function getDateRecrutement()
    {
        $pr = $this->getPremierRecrutement();
        return is_null($pr) ? null : $pr->getDate() ;
    }
}