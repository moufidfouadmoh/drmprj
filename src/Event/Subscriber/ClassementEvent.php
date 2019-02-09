<?php

namespace App\Event\Subscriber;

use App\Entity\Classement;
use Symfony\Component\EventDispatcher\Event;

class ClassementEvent extends Event
{
    const ON_ADD_CLASSEMENT ='user.add.classement';
    /**
     * @var Classement
     */
    private $classement;

    public function __construct(Classement $classement)
    {
        $this->classement = $classement;
    }

    /**
     * @return Classement
     */
    public function getClassement(): Classement
    {
        return $this->classement;
    }

    public function addClassement()
    {
        $data = $this->classement;
        $classements = $data->getUser()->getClassements();
        if(!$classements->isEmpty()){
            $array = $classements->toArray();
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
        $last->getUser()->setCurrentgroupe($last->getGroupe());
        $last->getUser()->setCurrentniveau($last->getNiveau());
        $last->getUser()->setCurrentechelon($last->getEchelon());
    }

}