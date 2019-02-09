<?php

namespace App\Manager;


interface ClassementManagerInterface extends BaseManagerInterface
{
    public function getClassementBySlugWithDetail($slug);
}