<?php

namespace App\Event\Subscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InterventionSubscriber implements EventSubscriberInterface
{

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            InterventionEvent::ON_ADD_INTERVENTION => 'onAddIntervention'
        ];
    }

    public function onAddIntervention(InterventionEvent $event)
    {
        $event->addIntervention();
    }
}