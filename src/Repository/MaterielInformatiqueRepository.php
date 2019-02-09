<?php

namespace App\Repository;

use App\Entity\Includes\Search\MaterielInformatiqueSearch;
use App\Entity\MaterielInformatique;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MaterielInformatique|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterielInformatique|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterielInformatique[]    findAll()
 * @method MaterielInformatique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielInformatiqueRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MaterielInformatique::class);
    }

    public function selectAll(MaterielInformatiqueSearch $searched = null)
    {
        $qb = $this->createQueryBuilder('materielinformatique');
        $qb
            ->leftJoin('materielinformatique.equipement','equipement')
            ->addSelect('equipement')
            ->leftJoin('materielinformatique.marque','marque')
            ->addSelect('marque')
        ;
        if(!is_null($searched)){

            if(!is_null($searched->getEquipements()) && !$searched->getEquipements()->isEmpty()){
                $qb
                    ->andWhere('equipement IN (:equipements)')
                    ->setParameter('equipements',$searched->getEquipements()->toArray())
                ;
            }
            if(!is_null($searched->getMarques()) && !$searched->getMarques()->isEmpty()){
                $qb
                    ->andWhere('marque IN (:marques)')
                    ->setParameter('marques',$searched->getMarques()->toArray())
                ;
            }
        }
        return $qb;
    }

    public function selectOneByEquipementAndMarque($equipement,$marque)
    {
        $qb = $this->createQueryBuilder('materielinformatique');
        $qb
            ->leftJoin('materielinformatique.equipement','equipement')
            ->addSelect('equipement')
            ->leftJoin('materielinformatique.marque','marque')
            ->addSelect('marque')
            ->andWhere('equipement.nom = :equipement')
            ->andWhere('marque.nom = :marque')
            ->setParameters([
                'equipement' => $equipement,
                'marque' => $marque
            ])
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('materielinformatique');
        $qb
            ->leftJoin('materielinformatique.equipement','equipement')
            ->addSelect('equipement')
            ->leftJoin('materielinformatique.marque','marque')
            ->addSelect('marque')
            ->leftJoin('materielinformatique.inventaireInformatiques','inventaireInformatiques')
            ->addSelect('inventaireInformatiques')
            ->leftJoin('inventaireInformatiques.bureau','bureau')
            ->addSelect('bureau')
            ->andWhere('materielinformatique.slug = :slug')
            ->setParameter('slug' , $slug)
        ;
        return $qb;
    }
}
