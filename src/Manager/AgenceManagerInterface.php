<?php

namespace App\Manager;


interface AgenceManagerInterface extends BaseManagerInterface
{
    public function getAgenceByNom($nom);
}