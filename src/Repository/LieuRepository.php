<?php

namespace App\Repository;


use App\Entity\Includes\Search\LieuSearch;
use App\Entity\Lieu;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Lieu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lieu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lieu[]    findAll()
 * @method Lieu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LieuRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Lieu::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function selectAll(LieuSearch $searched = null)
    {
        $qb = $this->createQueryBuilder('lieu');
        $qb
            ->leftJoin('lieu.agences','agences')
            ->addSelect('agences')
        ;
        if(!is_null($searched)){
            if($searched->getNom()){
                $qb
                    ->andWhere('lieu.nom LIKE :nom')
                    ->setParameter('nom','%'.$searched->getNom().'%')
                ;
            }
            if(!empty($searched->getIles())){
                $qb
                    ->andWhere('lieu.ile IN (:array)')
                    ->setParameter('array',$searched->getIles())
                ;
            }
        }
        return $qb;
    }

    /**
     * @param $nom string
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function selectOneByNom($nom)
    {
        $qb = $this->createQueryBuilder('lieu');
        $qb
            ->andWhere('lieu.nom = :nom')
            ->setParameter('nom', $nom)
        ;
        return $qb;
    }
}
