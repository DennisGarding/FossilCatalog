<?php

namespace App\Update;

class AvailableUpdate
{
    public function __construct(
        private readonly bool $hasUpdate,
        private readonly string $currentVersion,
        private readonly string $latestVersion,
    ) {}

    /**
     * @return array{hasUpdate: bool, currentVersion: string, latestVersion: string}
     */
    public function toArray(): array
    {
        return [
            'hasUpdate' => $this->hasUpdate,
            'currentVersion' => $this->currentVersion,
            'latestVersion' => $this->latestVersion,
        ];
    }
}
