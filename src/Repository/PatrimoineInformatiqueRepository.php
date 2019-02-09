<?php

namespace App\Repository;

use App\Entity\PatrimoineInformatique;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PatrimoineInformatique|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatrimoineInformatique|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatrimoineInformatique[]    findAll()
 * @method PatrimoineInformatique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatrimoineInformatiqueRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PatrimoineInformatique::class);
    }

    public function selectAll()
    {
        $qb = $this->createQueryBuilder('patrimoineinformatique');
        $qb
            ->leftJoin('patrimoineinformatique.user','user')
            ->addSelect('user')
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('patrimoineinformatique');
        $qb
            ->leftJoin('patrimoineinformatique.user','user')
            ->addSelect('user')
            ->leftJoin('patrimoineinformatique.inventaires','inventaires')
            ->addSelect('inventaires')
            ->leftJoin('inventaires.bureau','bureau')
            ->addSelect('bureau')
            ->leftJoin('inventaires.materielInformatique','materielInformatique')
            ->addSelect('materielInformatique')
            ->leftJoin('materielInformatique.equipement','equipement')
            ->addSelect('equipement')
            ->leftJoin('materielInformatique.marque','marque')
            ->addSelect('marque')
            ->andWhere('patrimoineinformatique.slug = :slug')
            ->setParameter('slug',$slug)
        ;
        return $qb;
    }
}
