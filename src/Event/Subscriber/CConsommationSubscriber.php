<?php

namespace App\Event\Subscriber;


use App\Entity\Affectation;
use App\Entity\Situation;
use App\Utils\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CConsommationSubscriber implements EventSubscriberInterface
{
    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsommationEvent::ON_CREATE => 'onCreate'
        ];
    }

    public function onCreate(ConsommationEvent $event)
    {
        $event->build();
    }
}