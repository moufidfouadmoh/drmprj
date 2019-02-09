<?php

namespace App\Voter;


use App\Entity\CConsommation;
use App\Entity\User;
use App\Utils\Type\Choice\RoleChoiceType;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CConsommationVoter extends Voter
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
        return in_array($attribute, [
                RoleChoiceType::EDIT_CONSOMMATION,
                RoleChoiceType::DELETE_CONSOMMATION
            ])
            &&( $subject instanceof CConsommation || (is_array($subject) && array_key_exists('fin',$subject) && array_key_exists('annee',$subject)));
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }


        switch ($attribute) {
            case RoleChoiceType::EDIT_CONSOMMATION:
                return $this->canEdit($subject);
                break;
            case RoleChoiceType::DELETE_CONSOMMATION:
                return $this->canDelete($subject);
                break;
        }

        return false;
    }

    private function canEdit($consommation)
    {
        if($consommation instanceof CConsommation){
            if($consommation->getDatefin($consommation->getDelaiaccorde()) < new \DateTime()){
                return false;
            }else{
                if($this->checker->isGranted(RoleChoiceType::ADMIN_CONGE)){
                    return true;
                }
            }
        }elseif (is_array($consommation)){
            if($consommation['fin'] < new \DateTime()){
                return false;
            }else{
                if($this->checker->isGranted(RoleChoiceType::ADMIN_CONGE)){
                    return true;
                }
            }
        }

        return false;
    }

    private function canDelete($consommation)
    {
        return $this->canEdit($consommation);
    }
}