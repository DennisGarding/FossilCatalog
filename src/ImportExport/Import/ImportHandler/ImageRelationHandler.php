<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\TableConfig;
use App\ImportExport\Types;
use Doctrine\DBAL\Query\QueryBuilder;

class ImageRelationHandler extends AbstractImportHandler
{
    public function getKey(): string
    {
        return Types::TYPE_IMAGE_RELATION;
    }

    protected function getTableName(): string
    {
        return 'fossil_image';
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
            (int) $data['image_id'],
            'image_id'
        );
    }

    private function createInsertQuery(array $data): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->insert($this->getTableName())
            ->values([
                'fossil_id' => ':fossil_id',
                'image_id' => ':image_id'
            ])
            ->setParameter('fossil_id', $data['fossil_id'])
            ->setParameter('image_id', $data['image_id']);
    }
}