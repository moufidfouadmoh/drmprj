<?php

namespace App\Utils;


use Doctrine\ORM\EntityManagerInterface;

trait AppRepoTrait
{
    /**
     * @return EntityManagerInterface
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->_em;
    }
}