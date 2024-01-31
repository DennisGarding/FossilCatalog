<?php

namespace App\DemoData\Random;

use App\Entity\EntityInterface;

class EntityObjectOptions
{
    /**
     * @var array<int, EntityInterface>
     */
    private array $entities;

    private int $length;

    public function __construct(int $length, array $entities)
    {
        $this->entities = $entities;
        $this->length = $length;
    }

    /**
     * @return array<int, EntityInterface>
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    public function getLength(): int
    {
        return $this->length;
    }
}