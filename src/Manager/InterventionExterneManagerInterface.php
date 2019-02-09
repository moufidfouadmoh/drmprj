<?php

namespace App\Manager;


interface InterventionExterneManagerInterface extends BaseManagerInterface
{
    public function getInterventionBySlugWithDetail($slug);
}