<?php

namespace App\Event\Subscriber;


use App\Entity\Cadrage;
use App\Utils\Type\Choice\SituationChoiceType;
use Symfony\Component\EventDispatcher\Event;

class CadrageEvent extends Event
{
    const ON_ADD_CADRAGE ='user.add.cadrage';
    /**
     * @var Cadrage
     */
    private $situation;
    /**
     * @var Cadrage
     */
    private $cadrage;

    public function __construct(Cadrage $cadrage)
    {
        $this->cadrage = $cadrage;
    }

    /**
     * @return Cadrage
     */
    public function getCadrage(): Cadrage
    {
        return $this->cadrage;
    }

    public function addCadrage()
    {
        $data = $this->cadrage;
        $cadrages = $data->getUser()->getCadrages();
        if(!$cadrages->isEmpty()){
            $array = $cadrages->toArray();
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
        $last->getUser()->setCurrentCategorie($last->getCategorie());
    }

}