<?php


namespace App\Utils\Type\Choice;


class YearChoiceType
{
    public static function getChoices($min,$max = 'current')
    {
        $years = range($min,($max === 'current' ? date('Y') : $max));
        return array_combine($years,$years);
    }
}