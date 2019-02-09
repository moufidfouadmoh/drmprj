<?php


namespace App\Utils\Type\Choice;


class EtatChoiceType
{
    const YES = true;
    const NO = false;
    protected static $choices = [
        self::YES => 'etat.yes',
        self::NO => 'etat.no',
    ];
    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}