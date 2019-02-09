<?php


namespace App\Utils\Type\Choice;


class InventaireChoiceType
{
    const AJOUT = 'TYPE_INVENTAIRE_AJOUT';
    const RETRAIT = 'TYPE_INVENTAIRE_RETRAIT';
    protected static $cas = [
        self::AJOUT => 'inventaire.cas.ajout',
        self::RETRAIT => 'inventaire.cas.retrait',
    ];
    public static function getCas()
    {
        return array_flip(static::$cas);
    }

    const UTILISATION = 'TYPE_INVENTAIRE_UTILISATION';
    const STOCKAGE = 'TYPE_INVENTAIRE_STOCKAGE';
    protected static $etats = [
        self::STOCKAGE => 'inventaire.etat.stockage',
        self::UTILISATION => 'inventaire.etat.utilisation'
    ];
    public static function getEtats()
    {
        return array_flip(static::$etats);
    }
}