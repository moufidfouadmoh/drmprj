<?php

namespace App\Event\Subscriber;

use App\Manager\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserManagerInterface
     */
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            SituationEvent::ON_ADD_SITUATION => 'onAddSituation',
            ClassementEvent::ON_ADD_CLASSEMENT => 'onAddClassement',
            AffectationEvent::ON_ADD_AFFECTATION => 'onAddAffectation',
            CadrageEvent::ON_ADD_CADRAGE => 'onAddCadrage',
        ];
    }

    public function onAddSituation(SituationEvent $event)
    {
        $event->addSituation();
    }

    public function onAddClassement(ClassementEvent $event)
    {
        $event->addClassement();
    }

    public function onAddCadrage(CadrageEvent $event)
    {
        $event->addCadrage();
    }

    public function onAddAffectation(AffectationEvent $event)
    {
        $event->addAffectation();
    }
}