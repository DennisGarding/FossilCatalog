<?php

namespace App\Static\Installation;

class AppSecret
{
    public const KEY = 'appSecret';

    public static function create(): string
    {
        $a = '0123456789abcdef';
        $secret = '';
        for ($i = 0; $i < 32; $i++) {
            $secret .= $a[rand(0, 15)];
        }

        return $secret;
    }
}