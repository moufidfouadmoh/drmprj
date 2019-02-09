<?php

namespace App\Utils\Type\Choice;

class RoleChoiceType
{
    const ADMIN_CONGE = 'ROLE_ADMIN_CONGE';
    const EDIT_CONSOMMATION = 'ROLE_EDIT_CONSOMMATION';
    const DELETE_CONSOMMATION = 'ROLE_DELETE_CONSOMMATION';

    const ADMIN_PERSONNEL = 'ROLE_ADMIN_PERSONNEL';

    const ADMIN_MATERIEL_INFORMATIQUE = 'ROLE_ADMIN_MATERIEL_INFORMATIQUE';
    const ADMIN_MATERIEL_MOBILIER = 'ROLE_ADMIN_MATERIEL_MOBILIER';
    const DELETE_MATERIEL = 'ROLE_DELETE_MATERIEL';

    const ADMIN_INTERVENTION_INTERNE = 'ROLE_ADMIN_INTERVENTION_INTERNE';
    const ADMIN_INTERVENTION_EXTERNE = 'ROLE_ADMIN_INTERVENTION_EXTERNE';

    const ADMIN_IP = 'ROLE_ADMIN_IP';

    protected static $choices = [
        //User::ROLE_DEFAULT => 'role.user',
        //User::ROLE_SUPER_ADMIN => 'role.superadmin',

        self::ADMIN_CONGE => 'role.admin.conge',

        self::ADMIN_PERSONNEL => 'role.admin.personnel',

        self::ADMIN_CONGE => 'role.admin.conge',

        self::ADMIN_MATERIEL_INFORMATIQUE => 'role.admin.materiel.informatique',
        self::ADMIN_MATERIEL_MOBILIER => 'role.admin.materiel.mobilier',

        self::ADMIN_INTERVENTION_INTERNE => 'role.admin.intervention.interne',
        self::ADMIN_INTERVENTION_EXTERNE => 'role.admin.intervention.externe',

        self::ADMIN_IP => 'role.admin.ip',
    ];
    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}