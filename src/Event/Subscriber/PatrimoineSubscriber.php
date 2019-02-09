<?php

namespace App\Event\Subscriber;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PatrimoineSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }
    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            PatrimoineEvent::ON_ADD_PATRIMOINE => 'onAddPatrimoine',
            PatrimoineEvent::ON_DELETE_PATRIMOINE => 'onDeletePatrimoine',
        ];
    }

    public function onAddPatrimoine(PatrimoineEvent $event)
    {
        $user = $this->token->getToken()->getUser();
        if($user instanceof User){
            $event->addPatrimoine($user);
        }
    }

    public function onDeletePatrimoine(PatrimoineEvent $event)
    {
        $event->deletePatrimoine();
    }
}