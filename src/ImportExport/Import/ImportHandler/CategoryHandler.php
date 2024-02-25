<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\TableConfig;
use App\ImportExport\Types;
use Doctrine\DBAL\Query\QueryBuilder;

class CategoryHandler extends AbstractImportHandler
{
    public function getKey(): string
    {
        return Types::TYPE_CATEGORY;
    }

    public function getTableName(): string
    {
        return 'category';
    }

    public function createQuery(array $data): QueryBuilder
    {
        if ($this->datasetExists(new TableConfig(TableConfig::TYPE_DATA, $this->getTableName(), (int) $data['id']))) {
            return $this->createUpdateQuery($data);
        }

        return $this->createInsertQuery($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function createUpdateQuery(array $data): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->update($this->getTableName())
            ->set('name', ':name')
            ->where('id = :id')
            ->setParameter('id', $data['id'])
            ->setParameter('name', $data['name']);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function createInsertQuery(array $data): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->insert($this->getTableName())
            ->values([
                'id' => ':id',
                'name' => ':name',
            ])
            ->setParameter('id', $data['id'])
            ->setParameter('name', $data['name']);
    }
}
