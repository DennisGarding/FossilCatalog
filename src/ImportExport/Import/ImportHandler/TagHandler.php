<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\TableConfig;
use App\ImportExport\Types;
use Doctrine\DBAL\Query\QueryBuilder;

class TagHandler extends AbstractImportHandler
{
    public function getKey(): string
    {
        return Types::TYPE_TAG;
    }

    protected function getTableName(): string
    {
        return 'tag';
    }

    public function createQuery(array $data): ?QueryBuilder
    {
        if ($this->datasetExists($this->createConfig($data))) {
            return $this->createUpdateQuery($data);
        }

        return $this->createInsertQuery($data);
    }

    private function createConfig(array $data): TableConfig
    {
        return new TableConfig(
            TableConfig::TYPE_DATA,
            $this->getTableName(),
            (int) $data['id']
        );
    }

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

    private function createUpdateQuery(array $data): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->update($this->getTableName())
            ->where('id = :id')
            ->set('name', ':name')
            ->setParameter('id', $data['id'])
            ->setParameter('name', $data['name']);
    }
}