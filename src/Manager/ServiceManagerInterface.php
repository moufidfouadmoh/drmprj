<?php

namespace App\Manager;


interface ServiceManagerInterface extends BaseManagerInterface
{
    public function getServiceBySlugWithDetail($slug);
}