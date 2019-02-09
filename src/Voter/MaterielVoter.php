<?php

namespace App\Voter;


use App\Entity\Materiel;
use App\Entity\User;
use App\Utils\Type\Choice\RoleChoiceType;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MaterielVoter extends Voter
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    public function __construct(AuthorizationCheckerInterface $checker)
    {
        $this->checker = $checker;
    }
    protected function supports($attribute, $subject)
    {
        return $attribute == RoleChoiceType::DELETE_MATERIEL
            &&( $subject instanceof Materiel || (is_array($subject) && array_key_exists('quantite',$subject)));
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        if($attribute == RoleChoiceType::DELETE_MATERIEL){
            return $this->canDelete($subject);
        }

        return false;
    }

    private function canDelete($materiel)
    {
        if($materiel instanceof Materiel){
            if($materiel->getQuantite() > 0){
                return false;
            }else{
                if($this->checker->isGranted(RoleChoiceType::ADMIN_MATERIEL_INFORMATIQUE)
                    || $this->checker->isGranted(RoleChoiceType::ADMIN_MATERIEL_MOBILIER)){
                    return true;
                }
            }
        }elseif (is_array($materiel)){
            if($materiel['quantite'] > 0){
                return false;
            }else{
                if($this->checker->isGranted(RoleChoiceType::ADMIN_MATERIEL_INFORMATIQUE)
                    || $this->checker->isGranted(RoleChoiceType::ADMIN_MATERIEL_MOBILIER)){
                    return true;
                }
            }
        }


        return false;
    }
}