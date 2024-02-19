<?php

namespace App\ImportExport\Import;

use App\ImportExport\MissingArrayKeyException;

class ImportStatus
{
    protected string $type;

    private ?string $path;

    protected int $inImportQueue;

    protected int $imported;

    protected bool $isFinished;

    private ?string $file;


    public function __construct(
        string $type,
        ?string $path = null,
        ?string $file = null,
        ?int   $inImportQueue = 0,
        ?int   $imported = 0,
        ?bool  $isFinished = false,
    ) {
        $this->type = $type;
        $this->path = $path;
        $this->file = $file;
        $this->inImportQueue = $inImportQueue ?? 0;
        $this->imported = $imported ?? 0;
        $this->isFinished = $isFinished ?? false;
    }

    /**
     * @throws MissingArrayKeyException
     *
     * @param array<string,mixed> $data
     */
    public function fromArray(array $data): ImportStatus
    {
        foreach ($this->getProperties() as $property) {
            if (!array_key_exists($property, $data)) {
                throw new MissingArrayKeyException($property);
            }

            $this->$property = $data[$property];
        }

        return $this;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this->getProperties() as $property) {
            $array[$property] = $this->$property;
        }

        return $array;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getInImportQueue(): int
    {
        return $this->inImportQueue;
    }

    public function getImported(): int
    {
        return $this->imported;
    }

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function add(int $count): void
    {
        $this->imported += $count;
    }

    public function finish(): void
    {
        $this->isFinished = true;
    }

    /**
     * @return array<int, string>
     */
    private function getProperties(): array
    {
        return [
            'type',
            'path',
            'file',
            'isFinished',
            'inImportQueue',
            'imported',
        ];
    }
}