<?php

namespace App\Utils;


class TokenGenerator
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';

    public static function getToken(int $length)
    {
        $maxNumber = strlen(self::ALPHABET);
        $token = '';

        for($i = 0; $i < $length; $i++){
            $token .= self::ALPHABET[random_int(0,$maxNumber-1)];
        }
        return $token;
    }

}