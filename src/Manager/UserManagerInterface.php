<?php

namespace App\Manager;


interface UserManagerInterface extends BaseManagerInterface
{
    public function getUserByUsernameOrEmail($value);
    public function getUserBySlugWithDetail($slug);
}