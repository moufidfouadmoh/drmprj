<?php

namespace App\Manager;


interface CConsommationManagerInterface extends BaseManagerInterface
{
    public function getCConsommationBySlugWithDetail($slug);
}