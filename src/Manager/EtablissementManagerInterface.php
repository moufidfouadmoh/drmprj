<?php

namespace App\Manager;


interface EtablissementManagerInterface extends BaseManagerInterface
{
    public function getEtablissementByNom($nom);
    public function getEtablissementLikeNom($nom);
}