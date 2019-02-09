<?php

namespace App\Repository;

use App\Entity\Cours;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cours[]    findAll()
 * @method Cours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cours::class);
    }

    public function selectByNom($nom)
    {
        $qb = $this->createQueryBuilder('cours');
        $qb
            ->andWhere('cours.nom = :nom')
            ->setParameter('nom', $nom)
        ;
        return $qb;
    }

    public function selectLikeNom($nom)
    {
        $qb = $this->createQueryBuilder('cours');
        $qb
            ->andWhere('cours.nom LIKE :nom')
            ->setParameter('nom', '%'.$nom.'%')
        ;
        return $qb;
    }
}
