<?php

namespace App\Event\Subscriber;


use App\Entity\Intervention;
use App\Utils\TokenGenerator;
use Symfony\Component\EventDispatcher\Event;

class InterventionEvent extends Event
{
    const ON_ADD_INTERVENTION = 'user.add.intervention';

    /** @var Intervention */
    private $intervention;

    /**
     * @return Intervention
     */
    public function getIntervention(): Intervention
    {
        return $this->intervention;
    }

    /**
     * @param Intervention $intervention
     */
    public function setIntervention(Intervention $intervention): void
    {
        $this->intervention = $intervention;
    }

    public function addIntervention()
    {
        if(is_null($this->intervention->getId())){
            $this->intervention->setReference(TokenGenerator::getToken(random_int(4,8)));
        }
    }
}