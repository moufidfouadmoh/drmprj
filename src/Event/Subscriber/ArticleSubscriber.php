<?php

namespace App\Event\Subscriber;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ArticleSubscriber implements EventSubscriberInterface
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
            ArticleEvent::ON_ADD_ARTICLE => 'onAddArticle',
        ];
    }

    public function onAddArticle(ArticleEvent $event)
    {
        $user = $this->token->getToken()->getUser();
        if($user instanceof User){
            $event->addArticle($user);
        }
    }
}