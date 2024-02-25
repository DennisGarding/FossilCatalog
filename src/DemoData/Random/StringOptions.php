<?php

namespace App\DemoData\Random;

use App\DemoData\Random\StringOptions\CharsetInterface;

class StringOptions
{
    private string $characters = '';

    /**
     * @param array<CharsetInterface> $characterSets
     */
    public function __construct(
        private readonly int $length,
        private readonly array $characterSets,
    ) {
        foreach ($this->characterSets as $characterSet) {
            $this->characters .= $characterSet->getCharacters();
        }
    }

    public function getCharacters(): string
    {
        return $this->characters;
    }

    public function getLength(): int
    {
        return $this->length;
    }
}
