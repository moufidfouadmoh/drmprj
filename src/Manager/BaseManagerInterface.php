<?php

namespace App\Manager;


interface BaseManagerInterface
{
    public function getRepository();
    public function save($entity);
    public function delete($entity);
}