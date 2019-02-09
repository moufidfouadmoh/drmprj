<?php

namespace App\Manager;


interface CategorieManagerInterface extends BaseManagerInterface
{
    public function getCategorieByNom($nom);
}