<?php

namespace App\Repository;

use App\Entity\Statut;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Statut|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statut|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statut[]    findAll()
 * @method Statut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Statut::class);
    }

    /**
     * @param $nom string
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function selectOneByNom($nom)
    {
        $qb = $this->createQueryBuilder('statut')
            ->andWhere('statut.nom = :nom')
            ->setParameter('nom', $nom)
        ;
        return $qb;
    }
}
