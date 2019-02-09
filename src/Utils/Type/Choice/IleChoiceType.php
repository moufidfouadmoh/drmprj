<?php


namespace App\Utils\Type\Choice;


class IleChoiceType
{
    const NGAZIDJA = 'ILE.NGAZIDJA';
    const NDZUANI = 'ILE.NDZUANI';
    const MWALI = 'ILE.MWALI';

    protected static $choices = [
        self::NGAZIDJA => 'ile.ngazidja',
        self::NDZUANI => 'ile.ndzuani',
        self::MWALI => 'ile.mwali'
    ];

    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}