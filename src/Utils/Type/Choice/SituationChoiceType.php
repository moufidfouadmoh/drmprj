<?php

namespace App\Utils\Type\Choice;


class SituationChoiceType
{

    const PREMIER_RECRU = 'SITUATION_PR';
    const STAGE = 'SITUATION_ST';
    const RECRU_COURS = 'SITUATION_RC';
    const DEPART = 'SITUATION_DE';

    protected static $choices = [
        self::PREMIER_RECRU => 'situation.type.pr',
        self::STAGE => 'situation.type.st',
        self::RECRU_COURS => 'situation.type.rc',
        self::DEPART => 'situation.type.de'
    ];

    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}