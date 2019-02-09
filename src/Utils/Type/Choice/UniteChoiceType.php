<?php


namespace App\Utils\Type\Choice;


class UniteChoiceType
{
    const PIECE = 'TYPE_UNITE_PIECE';
    const METRE = 'TYPE_UNITE_METRE';
    const CARTON = 'TYPE_UNITE_CARTON';
    protected static $choices = [
        self::PIECE => 'mesure.unite.piece',
        self::METRE => 'mesure.unite.metre',
        self::CARTON => 'mesure.unite.carton',
    ];
    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}