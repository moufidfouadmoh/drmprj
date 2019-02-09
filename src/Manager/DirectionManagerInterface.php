<?php

namespace App\Manager;


interface DirectionManagerInterface extends BaseManagerInterface
{
    public function getDirectionBySlugWithDetail($slug);
}