<?php


namespace App\Utils\Type\Choice;


class RequestedChoiceType
{
    const YES = true;
    const NO = false;
    protected static $choices = [
        self::YES => 'requested.yes',
        self::NO => 'requested.no',
    ];
    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}