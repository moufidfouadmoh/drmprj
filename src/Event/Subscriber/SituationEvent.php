<?php

namespace App\Event\Subscriber;


use App\Entity\Situation;
use App\Utils\Type\Choice\SituationChoiceType;
use Symfony\Component\EventDispatcher\Event;

class SituationEvent extends Event
{
    const ON_ADD_SITUATION ='user.add.situation';
    /**
     * @var Situation
     */
    private $situation;

    public function __construct(Situation $situation)
    {
        $this->situation = $situation;
    }

    /**
     * @return Situation
     */
    public function getSituation(): Situation
    {
        return $this->situation;
    }

    public function addSituation()
    {
        $data = $this->situation;
        if($data->getType() == SituationChoiceType::PREMIER_RECRU){
            $data->getUser()->setDaterecrutement($data->getDate());
        }
        $situations = $data->getUser()->getSituations();
        if(!$situations->isEmpty()){
            $array = $situations->toArray();
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
        $last->getUser()->setCurrentStatut($last->getStatut());
        $type = $last->getType();
        $last->getUser()->setDepart($type == SituationChoiceType::DEPART ? true : false);
    }

}