<?php

namespace App\Repository;

use App\Entity\Categorie;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    /**
     * @param $nom string
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function selectOneByNom($nom)
    {
        $qb = $this->createQueryBuilder('categorie');
        $qb
            ->andWhere('categorie.nom = :nom')
            ->setParameter('nom', $nom)
        ;
        return $qb;
    }
}
