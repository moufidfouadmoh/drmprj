<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Includes\Search\ArticleSearch;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function selectAll(ArticleSearch $search = null)
    {
        $qb = $this->createQueryBuilder('article');
        $qb
            ->leftJoin('article.user','user')
            ->addSelect('user')
            ->orderBy('article.createdAt','DESC')
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('article');
        $qb
            ->leftJoin('article.user','user')
            ->addSelect('user')
            ->andWhere('article.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }
}
