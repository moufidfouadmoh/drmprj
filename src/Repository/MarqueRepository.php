<?php

namespace App\Repository;

use App\Entity\Marque;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Marque|null find($id, $lockMode = null, $lockVersion = null)
 * @method Marque|null findOneBy(array $criteria, array $orderBy = null)
 * @method Marque[]    findAll()
 * @method Marque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarqueRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Marque::class);
    }

    public function selectByNom($nom)
    {
        $qb = $this->createQueryBuilder('marque');
        $qb
            ->andWhere('marque.nom = :nom')
            ->setParameter('nom', $nom)
        ;
        return $qb;
    }

    public function selectLikeNom($nom)
    {
        $qb = $this->createQueryBuilder('marque');
        $qb
            ->andWhere('marque.nom LIKE :nom')
            ->setParameter('nom', '%'.$nom.'%')
        ;
        return $qb;
    }
}
