<?php

namespace App\Repository;

use App\Entity\Includes\Search\RoleSearch;
use App\Entity\User;
use App\Entity\Includes\Search\UserSearch;
use App\Utils\AppRepoTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    use AppRepoTrait;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function selectAll(UserSearch $searched = null)
    {
        $qb = $this->createQueryBuilder('user');
        $qb
            ->leftJoin('user.currentStatut','currentStatut')
            ->addSelect('currentStatut')
            ->leftJoin('user.currentBureau','currentBureau')
            ->addSelect('currentBureau')
            ->leftJoin('user.currentFonction','currentFonction')
            ->addSelect('currentFonction')
            ->leftJoin('user.currentCategorie','currentCategorie')
            ->addSelect('currentCategorie')
        ;
        if(!is_null($searched)){
            if($searched->getGender()){
                $qb
                    ->andWhere('user.sexe = :sexe')
                    ->setParameter('sexe',$searched->getGender())
                ;
            }

            if($searched->getUser()){
                $qb
                    ->orWhere('user.nom LIKE :global')
                    ->orWhere('user.prenom LIKE :global')
                    ->orWhere('user.username LIKE :global')
                    ->orWhere('user.email LIKE :global')
                    ->setParameter('global','%'.$searched->getUser().'%')
                ;
            }
            if(!is_null($searched->getStatuts()) && !$searched->getStatuts()->isEmpty()){
                $qb
                    ->andWhere('currentStatut IN (:statuts)')
                    ->setParameter('statuts',$searched->getStatuts()->toArray())
                ;
            }
            if(!is_null($searched->getCategories()) && !$searched->getCategories()->isEmpty()){
                $qb
                    ->andWhere('currentCategorie IN (:categories)')
                    ->setParameter('categories',$searched->getCategories()->toArray())
                ;
            }
            if(!is_null($searched->getBureaus()) && !$searched->getBureaus()->isEmpty()){
                $qb
                    ->andWhere('currentBureau IN (:bureaus)')
                    ->setParameter('bureaus',$searched->getBureaus()->toArray())
                ;
            }
            if(!is_null($searched->getFonctions()) && !$searched->getFonctions()->isEmpty()){
                $qb
                    ->andWhere('currentFonction IN (:fonctions)')
                    ->setParameter('fonctions',$searched->getFonctions()->toArray())
                ;
            }
        }
        $qb
            ->andWhere('user.username != :admin')
            ->setParameter('admin', User::ADMIN)
        ;
        return $qb;
    }

    public function selectAllWithSituation()
    {
        $qb = $this->createQueryBuilder('user');
        $qb
            ->leftJoin('user.currentStatut','currentStatut')
            ->addSelect('currentStatut')
            ->leftJoin('user.currentAgence','currentAgence')
            ->addSelect('currentAgence')
            ->andWhere($qb->expr()->isNotNull('currentStatut'))
            ->andWhere($qb->expr()->isNotNull('currentAgence'))
            ->andWhere($qb->expr()->eq('user.depart','0'))
        ;
        return $qb;
    }
    public function selectOneByUsernameOrEmail($value)
    {
        $qb = $this->createQueryBuilder('user');
            $qb
            ->where('user.username = :username OR user.email = :email')
            ->setParameter('username', $value)
            ->setParameter('email', $value)
        ;
        return $qb;
    }

    public function selectOneBySlugWithDetail($slug)
    {
        $qb = $this->createQueryBuilder('user');
        $qb
            ->leftJoin('user.formations','formations')
            ->addSelect('formations')
            ->leftJoin('formations.adresse','adresse')
            ->addSelect('adresse')
            ->leftJoin('adresse.etablissement','etablissement')
            ->addSelect('etablissement')
            ->leftJoin('adresse.pays','pays')
            ->addSelect('pays')
            ->leftJoin('formations.cours','cours')
            ->addSelect('cours')
            ->leftJoin('formations.diplome','diplome')
            ->addSelect('diplome')
            ->leftJoin('user.situations','situations')
            ->addSelect('situations')
            ->leftJoin('situations.statut','statut')
            ->addSelect('statut')
            ->leftJoin('user.currentStatut','currentStatut')
            ->addSelect('currentStatut')
            ->leftJoin('user.cadrages','cadrages')
            ->addSelect('cadrages')
            ->leftJoin('cadrages.categorie','categorie')
            ->addSelect('categorie')
            ->leftJoin('user.currentCategorie','currentCategorie')
            ->addSelect('currentCategorie')
            ->leftJoin('user.classements','classements')
            ->addSelect('classements')
            ->leftJoin('user.affectations','affectations')
            ->addSelect('affectations')
            ->leftJoin('affectations.agenceDest','agenceDest')
            ->addSelect('agenceDest')
            ->leftJoin('affectations.agenceOrigin','agenceOrigin')
            ->addSelect('agenceOrigin')
            ->leftJoin('affectations.bureauDest','bureauDest')
            ->addSelect('bureauDest')
            ->leftJoin('affectations.bureauOrigin','bureauOrigin')
            ->addSelect('bureauOrigin')
            ->leftJoin('affectations.fonctionDest','fonctionDest')
            ->addSelect('fonctionDest')
            ->leftJoin('affectations.fonctionOrigin','fonctionOrigin')
            ->addSelect('fonctionOrigin')
            ->leftJoin('user.currentBureau','currentBureau')
            ->addSelect('currentBureau')
            ->leftJoin('user.currentFonction','currentFonction')
            ->addSelect('currentFonction')
            ->leftJoin('user.currentAgence','currentAgence')
            ->addSelect('currentAgence')
            ->leftJoin('user.conges','conges')
            ->addSelect('conges')
            ->leftJoin('conges.cmodele','cmodele')
            ->addSelect('cmodele')
            ->where('user.slug = :slug')
            ->setParameter('slug', $slug)
            ->andWhere('user.username != :admin')
            ->setParameter('admin', User::ADMIN)
        ;
        return $qb;
    }


    public function selectByRoles(RoleSearch $searched = null)
    {
        $default_length = strlen(User::ROLE_DEFAULT);
        $qb = $this->createQueryBuilder('user');

        if(!is_null($searched)){
            $qb
                ->andWhere("REGEXP(user.roles, '".implode(',',$searched->getRole())."') = 1")
            ;
        }else{
            $qb
                ->andWhere(
                    $qb->expr()->gt($qb->expr()->length('user.roles'),$default_length)
                )
            ;
        }

        $qb
            ->andWhere('user.username != :admin')
            ->setParameter('admin', User::ADMIN)
        ;
        return $qb;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        return $this->selectOneByUsernameOrEmail($username)->getQuery()->getOneOrNullResult();
    }
}
