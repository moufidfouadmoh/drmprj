<?php

namespace App\Repository;

use App\Entity\Classement;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Classement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classement[]    findAll()
 * @method Classement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassementRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Classement::class);
    }

    public function selectAll()
    {
        $qb = $this->createQueryBuilder('classement')
            ->leftJoin('classement.user','user')
            ->addSelect('user')
            ->orderBy('classement.date', 'ASC')
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('classement');
        $qb
            ->leftJoin('classement.user','user')
            ->addSelect('user')
            ->andWhere('classement.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }
}
