<?php


namespace App\Utils\Twig\Filters;


use Twig\TwigFilter;
use Twig\TwigFunction;

trait StringFilter
{
    private function stringFilter()
    {
        $filters = [
            new TwigFilter('ucfirst', array($this, 'ucFirst')),
            new TwigFilter('truncate', array($this, 'truncate')),
        ];

        return $filters;
    }


    public function ucFirst($value,$encoding = 'UTF8')
    {
        $strlen = mb_strlen($value, $encoding);
        $firstChar = mb_substr($value, 0, 1, $encoding);
        $then = mb_substr($value, 1, $strlen - 1, $encoding);

        return mb_strtoupper($firstChar, $encoding) . $then;
    }

    public function truncate($string, $limit, $break=".", $pad="...")
    {
        // return with no change if string is shorter than $limit
        if(strlen($string) <= $limit) return $string;

        // is $break present between $limit and the end of the string?
        if(false !== ($breakpoint = strpos($string, $break, $limit))) {
            if($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }

        return $string;
    }
}