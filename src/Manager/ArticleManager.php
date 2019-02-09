<?php

namespace App\Manager;


use App\Entity\Article;
use App\Repository\ArticleRepository;

class ArticleManager implements ArticleManagerInterface
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return ArticleRepository
     */
    private function getArticleRepository(): ArticleRepository
    {
        return $this->articleRepository;
    }

    public function getRepository()
    {
        return $this->getArticleRepository();
    }


    public function save($article)
    {
        if($article instanceof Article){
            if(is_null($article->getId())){
                $this->articleRepository->getEm()->persist($article);
            }
            $this->articleRepository->getEm()->flush();
            return $article;
        }
        return null;
    }

    public function delete($article)
    {
        if($article instanceof Article){
            $this->articleRepository->getEm()->remove($article);
            $this->articleRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getArticleBySlugWithDetail($slug)
    {
        $qb = $this->articleRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}