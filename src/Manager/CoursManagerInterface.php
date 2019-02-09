<?php

namespace App\Manager;


interface CoursManagerInterface extends BaseManagerInterface
{
    public function getCoursByNom($nom);
    public function getCoursLikeNom($nom);
}