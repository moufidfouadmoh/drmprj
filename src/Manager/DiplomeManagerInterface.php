<?php

namespace App\Manager;


interface DiplomeManagerInterface extends BaseManagerInterface
{
    public function getDiplomeByNom($nom);
    public function getDiplomeLikeNom($nom);
}