<?php

namespace App\Tests\Integration;

trait ContainerTrait
{
    /**
     * @before
     */
    protected function boot(): void
    {
        self::bootKernel();
    }
}
