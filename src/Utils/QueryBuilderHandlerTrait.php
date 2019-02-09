<?php

namespace App\Utils;


use Doctrine\ORM\QueryBuilder;

trait QueryBuilderHandlerTrait
{
    /**
     * @return QueryBuilder
     */
    public function setQueryBuilderList(\Closure $closure)
    {
        return call_user_func($closure);
    }
}