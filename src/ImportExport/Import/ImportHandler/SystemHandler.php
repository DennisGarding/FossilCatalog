<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\TableConfig;
use App\ImportExport\Types;
use Doctrine\DBAL\Query\QueryBuilder;

class SystemHandler extends AbstractImportHandler
{
    public function getKey(): string
    {
        return Types::TYPE_SYSTEM;
    }

    protected function getTableName(): string
    {
        return 'earth_age_system';
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createQuery(array $data): ?QueryBuilder
    {
        if ($this->datasetExists($this->createConfig($data))) {
            return $this->createUpdateQuery($data);
        }

        return $this->createInsertQuery($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function createConfig(array $data): TableConfig
    {
        return new TableConfig(
            TableConfig::TYPE_DATA,
            $this->getTableName(),
            (int) $data['id']
        );
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
                'active' => ':active',
                'custom' => ':custom',
            ])
            ->setParameter('id', $data['id'])
            ->setParameter('name', $data['name'])
            ->setParameter('active', $data['active'])
            ->setParameter('custom', $data['custom']);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function createUpdateQuery(array $data): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->update($this->getTableName())
            ->where('id = :id')
            ->set('name', ':name')
            ->set('active', ':active')
            ->set('custom', ':custom')
            ->setParameter('id', $data['id'])
            ->setParameter('name', $data['name'])
            ->setParameter('active', $data['active'])
            ->setParameter('custom', $data['custom']);
    }
}
