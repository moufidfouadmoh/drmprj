<?php

namespace App\Repository;

use App\Entity\Includes\Search\MaterielMobilierSearch;
use App\Entity\MaterielMobilier;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MaterielMobilier|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterielMobilier|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterielMobilier[]    findAll()
 * @method MaterielMobilier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielMobilierRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MaterielMobilier::class);
    }

    public function selectAll(MaterielMobilierSearch $searched = null)
    {
        $qb = $this->createQueryBuilder('materielmobilier');
        $qb
            ->leftJoin('materielmobilier.equipement','equipement')
            ->addSelect('equipement')
        ;
        if(!is_null($searched)){

            if(!is_null($searched->getEquipements()) && !$searched->getEquipements()->isEmpty()){
                $qb
                    ->andWhere('equipement IN (:equipements)')
                    ->setParameter('equipements',$searched->getEquipements()->toArray())
                ;
            }
        }
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('materielmobilier');
        $qb
            ->leftJoin('materielmobilier.equipement','equipement')
            ->addSelect('equipement')
            ->leftJoin('materielmobilier.inventaireMobiliers','inventaireMobiliers')
            ->addSelect('inventaireMobiliers')
            ->leftJoin('inventaireMobiliers.bureau','bureau')
            ->addSelect('bureau')
            ->andWhere('materielmobilier.slug = :slug')
            ->setParameter('slug' , $slug)
        ;
        return $qb;
    }
}
