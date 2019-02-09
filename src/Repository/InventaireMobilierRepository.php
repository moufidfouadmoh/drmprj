<?php

namespace App\Repository;

use App\Entity\InventaireMobilier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InventaireMobilier|null find($id, $lockMode = null, $lockVersion = null)
 * @method InventaireMobilier|null findOneBy(array $criteria, array $orderBy = null)
 * @method InventaireMobilier[]    findAll()
 * @method InventaireMobilier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventaireMobilierRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InventaireMobilier::class);
    }
}
