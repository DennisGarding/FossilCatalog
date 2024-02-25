<?php

namespace App\DemoData;

class EntityOptions
{
    public function __construct(
        public readonly string $entityClass,
        public readonly int $amount,
    ) {}
}
