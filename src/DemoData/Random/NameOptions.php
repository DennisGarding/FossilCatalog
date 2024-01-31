<?php

namespace App\DemoData\Random;

class NameOptions
{
    /**
     * @var array<int, string>
     */
    private array $avoid = [];

    /**
     * @param array<int, string> $names
     */
    public function __construct(
        private readonly array         $names,
        private readonly string        $divider,
        private readonly StringOptions $stringOptions,
    ) {}

    public function registerAvoid(string $name): void
    {
        $this->avoid[] = $name;
    }

    /**
     * @return array<int, string>
     */
    public function getNames(): array
    {
        return $this->names;
    }

    /**
     * @return array<int, string>
     */
    public function getAvoid(): array
    {
        return $this->avoid;
    }

    public function getStringOptions(): StringOptions
    {
        return $this->stringOptions;
    }

    public function getDivider(): string
    {
        return $this->divider;
    }
}