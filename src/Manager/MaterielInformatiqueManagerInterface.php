<?php

namespace App\Manager;


interface MaterielInformatiqueManagerInterface extends BaseManagerInterface
{
    public function getMaterielByEquipementAndMarque($equipement, $marque);
    public function getMaterielBySlugWithDetail($slug);
}