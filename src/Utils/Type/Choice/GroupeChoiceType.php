<?php

namespace App\Utils\Type\Choice;


class GroupeChoiceType
{
    const GROUPE_I = 'I';
    const GROUPE_II = 'II';
    const GROUPE_III = 'III';
    const GROUPE_IV = 'IV';

    protected static $choices = [
        self::GROUPE_I => 'I',
        self::GROUPE_II => 'II',
        self::GROUPE_III => 'III',
        self::GROUPE_IV => 'IV'
    ];

    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}