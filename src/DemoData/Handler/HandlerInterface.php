<?php

namespace App\DemoData\Handler;

use App\DemoData\EntityOptions;

interface HandlerInterface
{
    public function supports(): string;

    public function create(EntityOptions $options): void;
}
