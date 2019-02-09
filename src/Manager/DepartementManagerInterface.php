<?php

namespace App\Manager;


interface DepartementManagerInterface extends BaseManagerInterface
{
    public function getDepartementBySlugWithDetail($slug);
}