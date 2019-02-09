<?php

namespace App\Manager;


interface LieuManagerInterface extends BaseManagerInterface
{
    public function getLieuByNom($nom);
}