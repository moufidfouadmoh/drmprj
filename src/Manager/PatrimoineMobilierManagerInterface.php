<?php

namespace App\Manager;


interface PatrimoineMobilierManagerInterface extends BaseManagerInterface
{
    public function getPatrimoineBySlugWithDetail($slug);
}