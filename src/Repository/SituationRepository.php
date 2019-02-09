<?php

namespace App\Repository;

use App\Entity\Situation;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Situation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Situation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Situation[]    findAll()
 * @method Situation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SituationRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Situation::class);
    }

    public function selectAll()
    {
        $qb = $this->createQueryBuilder('situation');
        $qb
            ->leftJoin('situation.user','user')
            ->addSelect('user')
            ->leftJoin('situation.statut','statut')
            ->addSelect('statut')
            ->orderBy('situation.date', 'ASC')
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('situation');
        $qb
            ->leftJoin('situation.user','user')
            ->addSelect('user')
            ->leftJoin('situation.statut','statut')
            ->addSelect('statut')
            ->andWhere('situation.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }
}
