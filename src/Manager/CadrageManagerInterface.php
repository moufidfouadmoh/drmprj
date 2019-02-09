<?php

namespace App\Manager;


interface CadrageManagerInterface extends BaseManagerInterface
{
    public function getCadrageBySlugWithDetail($slug);
}