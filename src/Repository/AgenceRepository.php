<?php

namespace App\Repository;

use App\Entity\Agence;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Agence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agence[]    findAll()
 * @method Agence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgenceRepository extends ServiceEntityRepository
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Agence::class);
    }
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function selectAll()
    {
        $qb = $this->createQueryBuilder('agence');
        $qb
            ->leftJoin('agence.lieu','lieu')
            ->addSelect('lieu')
            ->leftJoin('agence.bureaus','bureaus')
            ->addSelect('bureaus')
            ->orderBy('agence.updatedAt', 'DESC')
        ;
        return $qb;
    }

    public function selectOneByNom($nom)
    {
        $qb = $this->createQueryBuilder('agence')
            ->andWhere('agence.nom = :nom')
            ->setParameter('nom', $nom)
        ;
        return $qb;
    }
}
