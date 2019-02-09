<?php


namespace App\Utils\Type\Choice;


class DelaiChoiceType
{
    const YES = true;
    const NO = false;
    protected static $choices = [
        self::YES => 'delai.yes',
        self::NO => 'delai.no',
    ];
    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}