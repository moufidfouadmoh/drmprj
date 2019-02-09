<?php


namespace App\Utils\Twig\Filters;

use App\Utils\Type\Choice\FonctionChoiceType;
use App\Utils\Type\Choice\InventaireChoiceType;
use App\Utils\Type\Choice\MentionChoiceType;
use App\Utils\Type\Choice\UniteChoiceType;
use Symfony\Component\Intl\Intl;
use Twig\TwigFilter;

trait FiltersTrait
{
    use SortingFilter,UserFilter,StringFilter;

    private function filtersTrait()
    {
        $filters = [
            new TwigFilter('mention', [$this, 'displayMention']),
            new TwigFilter('country', [$this, 'displayCountry']),
            new TwigFilter('bool_to_string', [$this, 'displayBoolToString']),
            new TwigFilter('type_fonction', [$this, 'displayTypeFonction']),
            new TwigFilter('unite_mesure', [$this, 'displayUniteMesure']),
            new TwigFilter('inventaire', [$this, 'displayInventaire']),
        ];
        return array_merge(
            $filters,
            $this->userFilter(),
            $this->sortingFilter(),
            $this->stringFilter()
        );
    }



    public function displayMention($mention)
    {
        switch ($mention){
            case MentionChoiceType::MENTION_PASSABLE:
                return $this->translator->trans('mention.passable');

            case MentionChoiceType::MENTION_ASSEZ_BIEN:
                return $this->translator->trans('mention.abien');

            case MentionChoiceType::MENTION_BIEN:
                return $this->translator->trans('mention.bien');

            case MentionChoiceType::MENTION_TRES_BIEN:
                return $this->translator->trans('mention.tbien');

            case MentionChoiceType::MENTION_EXCELLENT:
                return $this->translator->trans('mention.excellent');

            case MentionChoiceType::MENTION_HONORABLE:
                return $this->translator->trans('mention.honorable');

            case MentionChoiceType::MENTION_THONORABLE:
                return $this->translator->trans('mention.thonorable');
        }
    }

    public function displayCountry($countryCode, $displayLocale = null)
    {
        $country = Intl::getRegionBundle()->getCountryName($countryCode, $displayLocale);
        return $country;
    }

    public function displayBoolToString(bool $boolean,array $msgs,$params = [],$domain = 'messages')
    {
        switch ($boolean){
            case true:
                return $this->translator->trans($msgs[1],$params,$domain);

            case false:
                return $this->translator->trans($msgs[0],$params,$domain);
        }
    }

    public function displayUniteMesure($mesure)
    {
        switch ($mesure){
            case UniteChoiceType::PIECE:
                return $this->translator->trans('mesure.unite.piece');

            case UniteChoiceType::METRE:
                return $this->translator->trans('mesure.unite.metre');

            case UniteChoiceType::CARTON:
                return $this->translator->trans('mesure.unite.carton');
        }
    }

    public function displayInventaire($field)
    {
        switch ($field){
            case InventaireChoiceType::AJOUT:
                return $this->translator->trans('inventaire.cas.ajout');
            case InventaireChoiceType::RETRAIT:
                return $this->translator->trans('inventaire.cas.retrait');


            case InventaireChoiceType::UTILISATION:
                return $this->translator->trans('inventaire.etat.utilisation');
            case InventaireChoiceType::STOCKAGE:
                return $this->translator->trans('inventaire.etat.stockage');
        }
    }
}