<?php

namespace App\Manager;


interface AffectationManagerInterface extends BaseManagerInterface
{
    public function getAffectationBySlugWithDetail($slug);
}