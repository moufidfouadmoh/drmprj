<?php

namespace App\Repository;

use App\Entity\Service;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function selectAll()
    {
        $qb = $this->createQueryBuilder('service');
        $qb
            ->leftJoin('service.departement','departement')
            ->addSelect('departement')
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('service');
        $qb
            ->andWhere('service.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }
}
