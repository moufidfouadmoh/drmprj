<?php

namespace App\Repository;

use App\Entity\Direction;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Direction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Direction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Direction[]    findAll()
 * @method Direction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectionRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Direction::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function selectAll()
    {
        $qb = $this->createQueryBuilder('direction');
        $qb
            ->leftJoin('direction.departements','departements')
            ->addSelect('departements')
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('direction');
        $qb
            ->leftJoin('direction.departements','departements')
            ->addSelect('departements')
            ->leftJoin('direction.services','services')
            ->addSelect('services')
            ->leftJoin('direction.agences','agences')
            ->addSelect('agences')
            ->andWhere('direction.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }
}
