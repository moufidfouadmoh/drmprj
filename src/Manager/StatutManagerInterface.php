<?php

namespace App\Manager;


interface StatutManagerInterface extends BaseManagerInterface
{
    public function getStatutByNom($nom);
}