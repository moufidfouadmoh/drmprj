<?php

namespace App\Repository;

use App\Entity\InterventionExterne;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InterventionExterne|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterventionExterne|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterventionExterne[]    findAll()
 * @method InterventionExterne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionExterneRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InterventionExterne::class);
    }

    public function selectAll()
    {
        $qb = $this->createQueryBuilder('interventionexterne');
        $qb
            ->leftJoin('interventionexterne.users','users')
            ->addSelect('users')
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('interventionexterne');
        $qb
            ->leftJoin('interventionexterne.users','users')
            ->addSelect('users')
            ->andWhere('interventionexterne.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }
}
