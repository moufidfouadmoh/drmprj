<?php


namespace App\Utils\Type\Choice;


class ModeleChoiceType
{
    const SPECIAL = 'TYPE_CONGE_SPECIAL';
    const SANS_SOLDE = 'TYPE_CONGE_SANS_SOLDE';
    const ADMINISTRATIF = 'TYPE_CONGE_ADMINISTRATIF';
    const RECONNAISSANCE = 'TYPE_CONGE_RECONNAISSANCE';
    protected static $choices = [
        self::SPECIAL => 'conge.modele.special',
        self::SANS_SOLDE => 'conge.modele.sans_solde',
        self::ADMINISTRATIF => 'conge.modele.administratif',
        self::RECONNAISSANCE => 'conge.modele.reconnaissance',
    ];
    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}