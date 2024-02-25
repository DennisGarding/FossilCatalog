<?php

namespace App\DemoData\Random\StringOptions;

class LowerCaseLetters implements CharsetInterface
{
    public function getCharacters(): string
    {
        return 'abcdefghijklmnopqrstuvwxyz';
    }
}
