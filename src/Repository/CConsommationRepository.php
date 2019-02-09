<?php

namespace App\Repository;

use App\Entity\CConsommation;
use App\Entity\Includes\Search\CConsommationSearch;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CConsommation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CConsommation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CConsommation[]    findAll()
 * @method CConsommation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CConsommationRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CConsommation::class);
    }

    public function selectAll(CConsommationSearch $searched = null)
    {
        $qb = $this->createQueryBuilder('cconsommation');
        $qb
            ->leftJoin('cconsommation.user','user')
            ->addSelect('user')
            ->leftJoin('cconsommation.cmodele','cmodele')
            ->addSelect('cmodele')
            ->leftJoin('cconsommation.situation','situation')
            ->addSelect('situation')
            ->leftJoin('situation.statut','statut')
            ->addSelect('statut')
            ->leftJoin('cconsommation.affectation','affectation')
            ->addSelect('affectation')
            ->leftJoin('affectation.bureauDest','bureauDest')
            ->addSelect('bureauDest')
            ->leftJoin('affectation.agenceDest','agenceDest')
            ->addSelect('agenceDest')
            ->leftJoin('affectation.fonctionDest','fonctionDest')
            ->addSelect('fonctionDest')
        ;
        if(!is_null($searched)){
            if($searched->getUser()){
                $qb
                    ->orWhere('user.nom LIKE :global')
                    ->orWhere('user.prenom LIKE :global')
                    ->orWhere('user.username LIKE :global')
                    ->orWhere('user.email LIKE :global')
                    ->setParameter('global','%'.$searched->getUser().'%')
                ;
            }

            if(!is_null($searched->getModeles()) && !$searched->getModeles()->isEmpty()){
                $qb
                    ->andWhere('cmodele IN (:modeles)')
                    ->setParameter('modeles',$searched->getModeles()->toArray())
                ;
            }

            if(!is_null($searched->getAgences()) && !$searched->getAgences()->isEmpty()){
                $qb
                    ->andWhere('agenceDest IN (:agences)')
                    ->setParameter('agences',$searched->getAgences()->toArray())
                ;
            }

            if(!is_null($searched->getBureaus()) && !$searched->getBureaus()->isEmpty()){
                $qb
                    ->andWhere('bureauDest IN (:bureaus)')
                    ->setParameter('bureaus',$searched->getBureaus()->toArray())
                ;
            }

            if($searched->getDebut()){
                $qb
                    ->andWhere('cconsommation.datedebut <= :debut')
                    ->setParameter('debut',$searched->getDebut())
                ;
            }

            if($searched->getFin()){
                $qb
                    ->andWhere('cconsommation.fin >= :fin')
                    ->setParameter('fin',$searched->getFin())
                ;
            }
        }
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('cconsommation');
        $qb
            ->leftJoin('cconsommation.user','user')
            ->addSelect('user')
            ->leftJoin('cconsommation.cmodele','cmodele')
            ->addSelect('cmodele')
            ->leftJoin('cconsommation.situation','situation')
            ->addSelect('situation')
            ->leftJoin('situation.statut','statut')
            ->addSelect('statut')
            ->leftJoin('cconsommation.affectation','affectation')
            ->addSelect('affectation')
            ->leftJoin('affectation.bureauDest','bureauDest')
            ->addSelect('bureauDest')
            ->leftJoin('affectation.agenceDest','agenceDest')
            ->addSelect('agenceDest')
            ->leftJoin('affectation.fonctionDest','fonctionDest')
            ->addSelect('fonctionDest')
            ->andWhere('cconsommation.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }
}
