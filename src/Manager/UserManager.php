<?php

namespace App\Manager;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager implements UserManagerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * @return UserRepository
     */
    public function getRepository()
    {
        return $this->userRepository;
    }

    public function save($user)
    {
        if($user instanceof User){
             $password = is_null($user->getUsername()) ? User::PASSWORD : $user->getUsername();
            $user->setPassword($this->encoder->encodePassword($user,$password));
            if(is_null($user->getId())){
                $this->userRepository->getEm()->persist($user);
            }
            $this->userRepository->getEm()->flush();
            return $user;
        }
        return null;
    }

    public function delete($user)
    {
        if($user instanceof User){
            $this->userRepository->getEm()->remove($user);
            $this->userRepository->getEm()->flush();
            return true;
        }
        return false;
    }

    public function getUserByUsernameOrEmail($value)
    {
        $qb = $this->userRepository->selectOneByUsernameOrEmail($value);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getUserBySlugWithDetail($slug)
    {
        $qb = $this->userRepository->selectOneBySlugWithDetail($slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}