<?php

namespace App\Manager;


interface CModeleManagerInterface extends BaseManagerInterface
{
    public function getCModeleByNom($nom);
    public function getCModelesByNomAndStatuts($etat,$statuts = []);
    public function getCModeleBySlugWithDetail($slug);
}