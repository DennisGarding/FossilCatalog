<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\TableConfig;
use App\ImportExport\Types;
use Doctrine\DBAL\Query\QueryBuilder;

class TagRelationHandler extends AbstractImportHandler
{
    public function getKey(): string
    {
        return Types::TYPE_TAG_RELATION;
    }

    protected function getTableName(): string
    {
        return 'fossil_tag';
    }

    public function createQuery(array $data): ?QueryBuilder
    {
        if ($this->datasetExists($this->createConfig($data))) {
            return null;
        }

        return $this->createInsertQuery($data);
    }

    private function createConfig(array $data): TableConfig
    {
        return new TableConfig(
            TableConfig::TYPE_RELATION,
            $this->getTableName(),
            (int) $data['fossil_id'],
            'fossil_id',
            (int) $data['tag_id'],
            'tag_id'
        );
    }

    private function createInsertQuery(array $data): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->insert($this->getTableName())
            ->values([
                'fossil_id' => ':fossil_id',
                'tag_id' => ':tag_id',
            ])
            ->setParameter('fossil_id', $data['fossil_id'])
            ->setParameter('tag_id', $data['tag_id']);
    }
}