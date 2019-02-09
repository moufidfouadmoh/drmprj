<?php

namespace App\Repository;

use App\Entity\Affectation;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Affectation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Affectation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Affectation[]    findAll()
 * @method Affectation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffectationRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Affectation::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function selectAll()
    {
        $qb = $this->createQueryBuilder('affectation');
        $qb
            ->leftJoin('affectation.user','user')
            ->addSelect('user')
            ->orderBy('affectation.date', 'ASC')
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('affectation');
        $qb
            ->leftJoin('affectation.user','user')
            ->addSelect('user')
            ->leftJoin('affectation.bureauDest','bureauDest')
            ->addSelect('bureauDest')
            ->leftJoin('affectation.fonctionDest','fonctionDest')
            ->addSelect('fonctionDest')
            ->leftJoin('affectation.agenceDest','agenceDest')
            ->addSelect('agenceDest')
            ->andWhere('affectation.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }
}
