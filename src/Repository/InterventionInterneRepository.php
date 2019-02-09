<?php

namespace App\Repository;

use App\Entity\InterventionInterne;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InterventionInterne|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterventionInterne|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterventionInterne[]    findAll()
 * @method InterventionInterne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionInterneRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InterventionInterne::class);
    }

    public function selectAll()
    {
        $qb = $this->createQueryBuilder('interventioninterne');
        $qb
            ->leftJoin('interventioninterne.bureaus','bureaus')
            ->addSelect('bureaus')
            ->leftJoin('interventioninterne.users','users')
            ->addSelect('users')
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('interventioninterne');
        $qb
            ->leftJoin('interventioninterne.bureaus','bureaus')
            ->addSelect('bureaus')
            ->leftJoin('interventioninterne.users','users')
            ->addSelect('users')
            ->leftJoin('interventioninterne.materielInformatiques','materielInformatiques')
            ->addSelect('materielInformatiques')
            ->leftJoin('materielInformatiques.equipement','equipement')
            ->addSelect('equipement')
            ->leftJoin('materielInformatiques.marque','marque')
            ->addSelect('marque')
            ->andWhere('interventioninterne.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }

}
