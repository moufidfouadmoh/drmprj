<?php

namespace App\Manager;


interface EquipementManagerInterface extends BaseManagerInterface
{
    public function getEquipementByNom($nom);
    public function getEquipementLikeNom($nom);
}