<?php

namespace App\Repository;

use App\Entity\CModele;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CModele|null find($id, $lockMode = null, $lockVersion = null)
 * @method CModele|null findOneBy(array $criteria, array $orderBy = null)
 * @method CModele[]    findAll()
 * @method CModele[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CModeleRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CModele::class);
    }

    public function selectAll()
    {
        $qb = $this->createQueryBuilder('cmodele');
        $qb
            ->leftJoin('cmodele.statuts','statuts')
            ->addSelect('statuts')
        ;

        return $qb;
    }

    public function selectByEtatAndStatuts($etat,$statuts = [])
    {
        $qb = $this->createQueryBuilder('cmodele');
        $qb
            ->leftJoin('cmodele.statuts','statuts')
            ->addSelect('statuts')
            ->andWhere('statuts.etat = :etat')
            ->setParameter('etat',$etat)
        ;
        if(empty($statuts)){
            $qb
                ->andWhere('statuts.nom IN :statuts')
                ->setParameter('statuts',$statuts)
            ;
        }

        return $qb;
    }

    public function selectOneByNom($nom)
    {
        $qb = $this->createQueryBuilder('cmodele');
        $qb
            ->andWhere('cmodele.nom = :nom')
            ->setParameter('nom',$nom)
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('cmodele');
        $qb
            ->leftJoin('cmodele.statuts','statuts')
            ->addSelect('statuts')
            ->andWhere('cmodele.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }

}
