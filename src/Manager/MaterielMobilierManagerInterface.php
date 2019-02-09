<?php

namespace App\Manager;


interface MaterielMobilierManagerInterface extends BaseManagerInterface
{
    public function getMaterielBySlugWithDetail($slug);
}