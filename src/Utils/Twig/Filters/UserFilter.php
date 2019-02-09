<?php


namespace App\Utils\Twig\Filters;

use App\Entity\User;
use App\Utils\Type\Choice\SexeChoiceType;
use Twig\TwigFilter;

trait UserFilter
{
    private function userFilter()
    {
        $filters = [
            new TwigFilter('nom_prenom', [$this, 'displayNomPrenom']),
            new TwigFilter('sexe', [$this, 'displaySexe']),
            new TwigFilter('age', [$this, 'displayAge']),
            new TwigFilter('age_retraite', [$this, 'displayAgeRetraite']),
            new TwigFilter('periode_embauche', [$this, 'displayPeriodEmbauche']),
        ];
        return $filters;
    }

    public function displayNomPrenom(User $user)
    {
        return $user->getNomPrenom();
    }

    public function displaySexe($sexe)
    {
        switch ($sexe){
            case SexeChoiceType::SEXE_FEMININ:
                return $this->translator->trans('gender.woman');

            case SexeChoiceType::SEXE_MASCULIN:
                return $this->translator->trans('gender.man');
        }
    }

    public function displayAge(\DateTime $date)
    {
        $now = new \DateTime('now');
        $age = clone $date;
        $difference = $now->diff($age,true)->format('%Y');

        $an = $this->translator->trans(
            $difference > 1 ? 'age.an.plural' : 'age.an.singular',
            array(),
            'messages'
        );
        $result = $difference .' '. $an;
        return $result;
    }

    public function displayPeriodEmbauche(User $user,$withDetail = false)
    {
        $date = new \DateTime();
        if(!is_null($user->getPremierRecrutement())){
            $datepremierrecrutement = $user->getPremierRecrutement()->getDate();
            $daterecrutement = clone $datepremierrecrutement;
            $intervalle = $date->diff($daterecrutement,true);
            $nbre_an = $intervalle->format('%Y');
            $an = $nbre_an > 0 ? $nbre_an . $this->translator->trans(
                    $nbre_an > 1 ? 'age.an.plural' : 'age.an.singular',
                    array(),
                    'messages'
                ) : '';
            if($withDetail){
                $nbre_mois = $intervalle->format('%m');
                $nbre_jour = $intervalle->format('%d');

                $jour = $nbre_jour > 0 ? $nbre_jour . $this->translator->trans(
                        $nbre_jour > 1 ? 'age.jour.plural' : 'age.jour.singular',
                        array(),
                        'messages'
                    ) : '';

                $mois = $nbre_mois > 0 ? $nbre_mois . $this->translator->trans(
                        'age.mois.plural',
                        array(),
                        'messages'
                    ) : '';

                $result = $an .' '. $mois .' '. $jour;
            }else{
                $result = $nbre_an .' '. $an;
            }
        }else{
            $result = null;
        }


        return $result;
    }

    public function displayAgeRetraite(User $user)
    {
        $result = null;
        $ageRetraite = $user->getAgeRetraite();
        if(!is_null($ageRetraite)){
            $an = $this->translator->trans(
                $ageRetraite > 1 ? 'age.an.plural' : 'age.an.singular',
                array(),
                'messages'
            );
            $result = $ageRetraite .' '. $an;
        }
        return $result;
    }
}