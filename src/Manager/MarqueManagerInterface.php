<?php

namespace App\Manager;


interface MarqueManagerInterface extends BaseManagerInterface
{
    public function getMarqueByNom($nom);
    public function getMarqueLikeNom($nom);
}