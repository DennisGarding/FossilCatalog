<?php

namespace App\ImportExport\Import;

class TableConfig
{
    public const TYPE_RELATION = 'relation';
    public const TYPE_DATA = 'data';

    public function __construct(
        private readonly string  $type,
        private readonly string  $tableName,
        private readonly int     $idOne,
        private readonly ?string $columnOneName = 'id',
        private readonly ?int    $idTwo = 0,
        private readonly ?string $columnTwoName = 'id',
    ) {}

    public function getType(): string
    {
        return $this->type;
    }

    public function getIdOne(): int
    {
        return $this->idOne;
    }

    public function getColumnOneName(): string
    {
        return $this->columnOneName;
    }

    public function getIdTwo(): int
    {
        return $this->idTwo;
    }

    public function getColumnTwoName(): string
    {
        return $this->columnTwoName;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }
}