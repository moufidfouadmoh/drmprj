<?php

namespace App\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * Class Password
 * @Annotation
 */
class Password extends Constraint
{
    public $message;
}