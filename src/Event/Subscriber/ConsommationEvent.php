<?php

namespace App\Event\Subscriber;


use App\Entity\Affectation;
use App\Entity\CConsommation;
use App\Entity\Situation;
use App\Entity\User;
use App\Utils\TokenGenerator;
use Symfony\Component\EventDispatcher\Event;

class ConsommationEvent extends Event
{
    const ON_CREATE = 'cconsomation.create';
    /**
     * @var CConsommation
     */
    private $consommation;

    public function __construct(CConsommation $consommation)
    {
        $this->consommation = $consommation;
    }

    /**
     * @return CConsommation
     */
    public function getConsommation(): CConsommation
    {
        return $this->consommation;
    }

    public function build(User $owner = null)
    {
        $consommation = $this->consommation;
        $user = is_null($owner) ? $consommation->getUser() : $owner;
        $affectation = $user->getAffectations()->filter(function (Affectation $affectation){
            return $affectation->getEnabled();
        })->first();
        $situation = $user->getSituations()->filter(function (Situation $situation){
            return $situation->getEnabled();
        })->first();
        $consommation->setAffectation($affectation);
        $consommation->setSituation($situation);
        if(is_null($consommation->getId())){
            $consommation->setReference(TokenGenerator::getToken(random_int(4,8)));
        }
        $consommation->setFin(!is_null($consommation->getDelaiaccorde()) ? $consommation->getDatefin($consommation->getDelaiaccorde()) : null);
    }
}