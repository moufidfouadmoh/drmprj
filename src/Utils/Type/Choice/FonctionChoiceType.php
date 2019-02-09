<?php


namespace App\Utils\Type\Choice;


class FonctionChoiceType
{
    const PRINCIPALE = 'PRINCIPALE';
    const EXECUTANT = 'EXECUTANT';

    protected static $choices = [
        self::PRINCIPALE => 'fonction.type.principale',
        self::EXECUTANT => 'fonction.type.executant',

    ];

    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}