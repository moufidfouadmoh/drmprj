<?php

namespace App\Utils\Type\Choice;


class DirectionChoiceType
{
    const GENERALE = 'DIRECTION.TYPE.GENERALE';
    const REGIONALE = 'DIRECTION.TYPE.REGIONALE';

    protected static $choices = [
        self::GENERALE => 'direction.type.generale',
        self::REGIONALE => 'direction.type.regionale',
    ];

    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}