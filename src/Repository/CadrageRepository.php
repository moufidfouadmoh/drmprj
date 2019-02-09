<?php

namespace App\Repository;

use App\Entity\Cadrage;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Cadrage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cadrage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cadrage[]    findAll()
 * @method Cadrage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CadrageRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cadrage::class);
    }

    public function selectAll()
    {
        $qb = $this->createQueryBuilder('cadrage')
            ->leftJoin('cadrage.user','user')
            ->addSelect('user')
            ->leftJoin('cadrage.categorie','categorie')
            ->addSelect('categorie')
            ->orderBy('cadrage.date', 'ASC')
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('cadrage');
        $qb
            ->leftJoin('cadrage.user','user')
            ->addSelect('user')
            ->leftJoin('cadrage.categorie','categorie')
            ->addSelect('categorie')
            ->andWhere('cadrage.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }

}
