<?php

namespace App\Utils;


use App\Entity\CModele;
use App\Utils\Type\Choice\ModeleChoiceType;

trait CongeFormBuilder
{
    private static function checkForFieldDelaimin(CModele $modele)
    {
        return (!is_null($modele->getDelaimin()) && (!$modele->getFixe()));
    }

    private static function checkForFieldDelaimax(CModele $modele)
    {
        return (!is_null($modele->getDelaimax()) && (!$modele->getFixe()));
    }

    private static function checkForFieldAnnee(CModele $modele)
    {
        return $modele->getType() == ModeleChoiceType::ADMINISTRATIF;
    }

    static private function buildOptionsFromModele(CModele $modele)
    {
        $result = array(
            'annee' => self::checkForFieldAnnee($modele),
            'delaimin' => self::checkForFieldDelaimin($modele),
            'delaimax' => self::checkForFieldDelaimax($modele),
            'Nminyears' => !is_null($modele->getDelaimin()) ? (int)$modele->getDelaimin()->format('%Y') : 0,
            'Nminmonths' => !is_null($modele->getDelaimin()) ? (int)$modele->getDelaimin()->format('%M') : 0,
            'Nmindays' => !is_null($modele->getDelaimin()) ? (int)$modele->getDelaimin()->format('%D') : 0,
            'Nmaxyears' => !is_null($modele->getDelaimax()) ? (int)$modele->getDelaimax()->format('%Y') : 0,
            'Nmaxmonths' => !is_null($modele->getDelaimax()) ? (int)$modele->getDelaimax()->format('%M') : 0,
            'Nmaxdays' => !is_null($modele->getDelaimax()) ? (int)$modele->getDelaimax()->format('%D') : 0,
            'motif' => $modele->getJustificatif() ? true : false
        );


        return $result;
    }

    static public function buildCreateOptionsFromModele(CModele $modele)
    {
        $result = array_merge(self::buildOptionsFromModele($modele),array(
            'create' => true
        ));

        return $result;
    }

    static private function range($int)
    {
        $range = [];
        for ($i=1;$i<=$int;$i++){
            array_push($range,$i);
        }
        return $range;
    }
}