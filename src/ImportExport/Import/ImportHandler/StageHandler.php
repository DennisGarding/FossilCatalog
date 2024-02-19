<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\TableConfig;
use App\ImportExport\Types;
use Doctrine\DBAL\Query\QueryBuilder;

class StageHandler extends AbstractImportHandler
{
    public function getKey(): string
    {
        return Types::TYPE_STAGE;
    }

    protected function getTableName(): string
    {
        return 'earth_age_stage';
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
                'earth_age_series_id' => ':earth_age_series_id',
                'name' => ':name',
                'custom' => ':custom',
            ])
            ->setParameter('id', $data['id'])
            ->setParameter('earth_age_series_id', $data['earth_age_series_id'])
            ->setParameter('name', $data['name'])
            ->setParameter('custom', $data['custom']);
    }

    private function createUpdateQuery(array $data): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->update($this->getTableName())
            ->where('id = :id')
            ->set('earth_age_series_id', ':earth_age_series_id')
            ->set('name', ':name')
            ->set('custom', ':custom')
            ->setParameter('id', $data['id'])
            ->setParameter('earth_age_series_id', $data['earth_age_series_id'])
            ->setParameter('name', $data['name'])
            ->setParameter('custom', $data['custom']);
    }
}