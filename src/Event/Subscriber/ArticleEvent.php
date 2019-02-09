<?php

namespace App\Event\Subscriber;


use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class ArticleEvent extends Event
{
    const ON_ADD_ARTICLE = 'user.add.article';

    /** @var Article */
    private $article;

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }

    public function addArticle(User $user)
    {
        $this->article->setUser($user);
    }
}