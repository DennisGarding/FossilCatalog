<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\TableConfig;
use App\ImportExport\Types;
use Doctrine\DBAL\Query\QueryBuilder;

class CategoryRelationHandler extends AbstractImportHandler
{
    public function getKey(): string
    {
        return Types::TYPE_CATEGORY_RELATION;
    }

    public function getTableName(): string
    {
        return 'fossil_category';
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createQuery(array $data): ?QueryBuilder
    {
        if ($this->datasetExists($this->createConfig($data))) {
            return null;
        }

        return $this->createInsertQuery($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function createConfig(array $data): TableConfig
    {
        return new TableConfig(
            TableConfig::TYPE_RELATION,
            $this->getTableName(),
            (int) $data['fossil_id'],
            'fossil_id',
            (int) $data['category_id'],
            'category_id'
        );
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return QueryBuilder
     */
    private function createInsertQuery(array $data): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->insert($this->getTableName())
            ->values([
                'fossil_id' => ':fossilId',
                'category_id' => ':categoryId'
            ])
            ->setParameter('fossilId', $data['fossil_id'])
            ->setParameter('categoryId', $data['category_id']);
    }
}