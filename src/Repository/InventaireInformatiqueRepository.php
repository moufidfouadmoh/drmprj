<?php

namespace App\Repository;

use App\Entity\InventaireInformatique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InventaireInformatique|null find($id, $lockMode = null, $lockVersion = null)
 * @method InventaireInformatique|null findOneBy(array $criteria, array $orderBy = null)
 * @method InventaireInformatique[]    findAll()
 * @method InventaireInformatique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventaireInformatiqueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InventaireInformatique::class);
    }
}
