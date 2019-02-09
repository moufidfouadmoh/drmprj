<?php

namespace App\Repository;

use App\Entity\Departement;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Departement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Departement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Departement[]    findAll()
 * @method Departement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartementRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Departement::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function selectAll()
    {
        $qb = $this->createQueryBuilder('departement');
        $qb
            ->leftJoin('departement.services','services')
            ->addSelect('services')
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('departement');
        $qb
            ->andWhere('departement.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }
}
