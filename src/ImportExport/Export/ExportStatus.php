<?php

namespace App\ImportExport\Export;

use App\ImportExport\MissingArrayKeyException;

class ExportStatus
{
    protected string $type;

    protected int $inExportQueue;

    protected int $exported;

    protected bool $isFinished;

    public function __construct(
        string $type,
        ?int   $inExportQueue = 0,
        ?int   $exported = 0,
        ?bool  $isFinished = false,
    ) {
        $this->type = $type;
        $this->inExportQueue = $inExportQueue ?? 0;
        $this->exported = $exported ?? 0;
        $this->isFinished = $isFinished ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this->getProperties() as $property) {
            $array[$property] = $this->$property;
        }

        return $array;
    }

    /**
     * @param array<string,mixed> $data
     */
    public function fromArray(array $data): ExportStatus
    {
        foreach ($this->getProperties() as $property) {
            if (!array_key_exists($property, $data)) {
                throw new MissingArrayKeyException($property);
            }

            $this->$property = $data[$property];
        }

        return $this;
    }

    public function add(int $count): void
    {
        $this->exported += $count;
    }

    public function finish(): void
    {
        $this->isFinished = true;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getInExportQueue(): int
    {
        return $this->inExportQueue;
    }

    public function getExported(): int
    {
        return $this->exported;
    }

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    /**
     * @return array<int, string>
     */
    private function getProperties(): array
    {
        return [
            'type',
            'isFinished',
            'inExportQueue',
            'exported',
        ];
    }
}