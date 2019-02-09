<?php

namespace App\Manager;


interface PatrimoineInformatiqueManagerInterface extends BaseManagerInterface
{
    public function getPatrimoineBySlugWithDetail($slug);
}