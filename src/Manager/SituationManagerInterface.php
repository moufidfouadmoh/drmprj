<?php

namespace App\Manager;


interface SituationManagerInterface extends BaseManagerInterface
{
    public function getSituationBySlugWithDetail($slug);
}