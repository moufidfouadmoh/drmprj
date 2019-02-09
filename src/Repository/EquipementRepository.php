<?php

namespace App\Repository;

use App\Entity\Equipement;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Equipement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipement[]    findAll()
 * @method Equipement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipementRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Equipement::class);
    }

    public function selectByNom($nom)
    {
        $qb = $this->createQueryBuilder('equipement');
        $qb
            ->andWhere('equipement.nom = :nom')
            ->setParameter('nom', $nom)
        ;
        return $qb;
    }

    public function selectLikeNom($nom)
    {
        $qb = $this->createQueryBuilder('equipement');
        $qb
            ->andWhere('equipement.nom LIKE :nom')
            ->setParameter('nom', '%'.$nom.'%')
        ;
        return $qb;
    }
}
