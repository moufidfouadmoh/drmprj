<?php

namespace App\Repository;

use App\Entity\Diplome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Diplome|null find($id, $lockMode = null, $lockVersion = null)
 * @method Diplome|null findOneBy(array $criteria, array $orderBy = null)
 * @method Diplome[]    findAll()
 * @method Diplome[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiplomeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Diplome::class);
    }

    public function selectByNom($nom)
    {
        $qb = $this->createQueryBuilder('diplome');
        $qb
            ->andWhere('diplome.nom = :nom')
            ->setParameter('nom', $nom)
        ;
        return $qb;
    }

    public function selectLikeNom($nom)
    {
        $qb = $this->createQueryBuilder('diplome');
        $qb
            ->andWhere('diplome.nom LIKE :nom')
            ->setParameter('nom', '%'.$nom.'%')
        ;
        return $qb;
    }
}
