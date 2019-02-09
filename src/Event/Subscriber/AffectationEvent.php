<?php

namespace App\Event\Subscriber;


use App\Entity\Affectation;
use App\Utils\Type\Choice\SituationChoiceType;
use Symfony\Component\EventDispatcher\Event;

class AffectationEvent extends Event
{
    const ON_ADD_AFFECTATION ='user.add.affectation';
    /**
     * @var Affectation
     */
    private $affectation;

    public function __construct(Affectation $affectation)
    {
        $this->affectation = $affectation;
    }

    /**
     * @return Affectation
     */
    public function getAffectation(): Affectation
    {
        return $this->affectation;
    }

    public function addAffectation()
    {
        $data = $this->affectation;
        $affectations = $data->getUser()->getAffectations();
        if(!$affectations->isEmpty()){
            $array = $affectations->toArray();
            foreach ($array as $item){
                if($item->getDate() > $data->getDate()){
                    $last = $item;
                    $data->setEnabled(false);
                }else{
                    $last = $data;
                }
            }

        }else{
            $last = $data;
        }

        $last->setEnabled(true);
        $last->getUser()->setCurrentBureau($last->getBureauDest());
        $last->getUser()->setCurrentFonction($last->getFonctionDest());
        $last->getUser()->setCurrentAgence($last->getAgenceDest());
    }

}