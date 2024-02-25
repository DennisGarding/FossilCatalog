<?php

namespace App\DemoData\Random\StringOptions;

class UpperCaseLetters implements CharsetInterface
{
    public function getCharacters(): string
    {
        return 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
}
