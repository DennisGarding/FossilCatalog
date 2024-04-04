<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\TableConfig;
use App\ImportExport\Types;
use Doctrine\DBAL\Query\QueryBuilder;

class SettingsHandler extends AbstractImportHandler
{
    public function getKey(): string
    {
        return Types::TYPE_SETTINGS;
    }

    protected function getTableName(): string
    {
        return 'settings';
    }

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
    private function createUpdateQuery(array $data): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->update($this->getTableName())
            ->where('id = :id')
            ->set('banner_id', ':banner_id')
            ->set('brand', ':brand')
            ->setParameter('id', $data['id'])
            ->setParameter('banner_id', $data['banner_id'])
            ->setParameter('brand', $data['brand']);
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
                'banner_id' => ':banner_id',
                'brand' => ':brand',
            ])
            ->setParameter('id', $data['id'])
            ->setParameter('banner_id', $data['banner_id'])
            ->setParameter('brand', $data['brand']);
    }
}
