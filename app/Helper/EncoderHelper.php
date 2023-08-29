<?php

namespace App\Helper;

class EncoderHelper
{
    private static $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function custom_encode($number)
    {
        $result = '';
        $base = strlen(self::$characters);

        do {
            $remainder = $number % $base;
            $number = intval($number / $base);
            $result .= self::$characters[$remainder];
        } while ($number > 0);

        return strrev($result);
    }

    public static function custom_decode($string)
    {
        $result = 0;
        $base = strlen(self::$characters);

        for ($i = 0, $length = strlen($string); $i < $length; $i++) {
            $result = $result * $base + strpos(self::$characters, $string[$i]);
        }

        return $result;
    }
}