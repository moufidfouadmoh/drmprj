<?php

namespace App\Repository;

use App\Entity\PatrimoineMobilier;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PatrimoineMobilier|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatrimoineMobilier|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatrimoineMobilier[]    findAll()
 * @method PatrimoineMobilier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatrimoineMobilierRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PatrimoineMobilier::class);
    }

    public function selectAll()
    {
        $qb = $this->createQueryBuilder('patrimoinemobilier');
        $qb
            ->leftJoin('patrimoinemobilier.user','user')
            ->addSelect('user')
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('patrimoinemobilier');
        $qb
            ->leftJoin('patrimoinemobilier.inventaires','inventaires')
            ->addSelect('inventaires')
            ->leftJoin('inventaires.bureau','bureau')
            ->addSelect('bureau')
            ->leftJoin('inventaires.materielMobilier','materielMobilier')
            ->addSelect('materielMobilier')
            ->leftJoin('materielMobilier.equipement','equipement')
            ->addSelect('equipement')
            ->andWhere('patrimoinemobilier.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }
}
