<?php

namespace App\Manager;


interface InterventionInterneManagerInterface extends BaseManagerInterface
{
    public function getInterventionBySlugWithDetail($slug);
}