<?php


namespace App\Utils\Type\Choice;


class SexeChoiceType
{
    const SEXE_MASCULIN = 'M';
    const SEXE_FEMININ = 'F';
    protected static $choices = [
        self::SEXE_MASCULIN => 'gender.man',
        self::SEXE_FEMININ => 'gender.woman',
    ];
    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}