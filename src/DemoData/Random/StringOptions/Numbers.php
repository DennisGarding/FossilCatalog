<?php

namespace App\DemoData\Random\StringOptions;

class Numbers implements CharsetInterface
{
    public function getCharacters(): string
    {
        return '0123456789';
    }
}
