<?php

namespace App\Repository;

use App\Entity\Etablissement;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Etablissement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etablissement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etablissement[]    findAll()
 * @method Etablissement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtablissementRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Etablissement::class);
    }

    public function selectByNom($nom)
    {
        $qb = $this->createQueryBuilder('etablissement');
        $qb
            ->andWhere('etablissement.nom = :nom')
            ->setParameter('nom', $nom)
        ;
        return $qb;
    }

    public function selectLikeNom($nom)
    {
        $qb = $this->createQueryBuilder('etablissement');
        $qb
            ->andWhere('etablissement.nom LIKE :nom')
            ->setParameter('nom', '%'.$nom.'%')
            ;
        return $qb;
    }

}
