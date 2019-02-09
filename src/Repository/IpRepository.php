<?php

namespace App\Repository;

use App\Entity\Includes\Search\IpSearch;
use App\Entity\Ip;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ip[]    findAll()
 * @method Ip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IpRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ip::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function selectAll(IpSearch $searched = null)
    {
        $qb = $this->createQueryBuilder('ip');
        $qb
            ->leftJoin('ip.agence','agence')
            ->addSelect('agence')
            ->leftJoin('ip.bureau','bureau')
            ->addSelect('bureau')
        ;
        if(!is_null($searched)){
            if($searched->getAddress()){
                $qb
                    ->andWhere('ip.address LIKE :address')
                    ->setParameter('address','%'.$searched->getAddress().'%')
                ;
            }
        }

        if(!is_null($searched->getBureaus()) && !$searched->getBureaus()->isEmpty()){
            $qb
                ->andWhere('bureau IN (:bureaus)')
                ->setParameter('bureaus',$searched->getBureaus()->toArray())
            ;
        }
        if(!is_null($searched->getAgences()) && !$searched->getAgences()->isEmpty()){
            $qb
                ->andWhere('agence IN (:agences)')
                ->setParameter('agences',$searched->getAgences()->toArray())
            ;
        }

        return $qb;
    }
}
