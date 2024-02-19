<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\TableConfig;
use App\ImportExport\Types;
use Doctrine\DBAL\Query\QueryBuilder;

class FormFieldHandler extends AbstractImportHandler
{
    public function getKey(): string
    {
        return Types::TYPE_FORM_FIELD;
    }

    protected function getTableName(): string
    {
        return 'fossil_form_field';
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
    private function createConfig(array $data)
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
                'field_order' => ':fieldOrder',
                'field_name' => ':fieldName',
                'field_label' => ':fieldLabel',
                'field_type' => ':fieldType',
                'allow_blank' => ':allowBlank',
                'is_required_default' => ':isRequiredDefault',
                'active' => ':active',
                'show_always' => ':showAlways',
                'field_group' => ':fieldGroup',
                'show_in_overview' => ':showInOverview',
            ])
            ->setParameter('id', $data['id'])
            ->setParameter('fieldOrder', $data['field_order'])
            ->setParameter('fieldName', $data['field_name'])
            ->setParameter('fieldLabel', $data['field_label'])
            ->setParameter('fieldType', $data['field_type'])
            ->setParameter('allowBlank', $data['allow_blank'])
            ->setParameter('isRequiredDefault', $data['is_required_default'])
            ->setParameter('active', $data['active'])
            ->setParameter('showAlways', $data['show_always'])
            ->setParameter('fieldGroup', $data['field_group'])
            ->setParameter('showInOverview', $data['show_in_overview']);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function createUpdateQuery(array $data): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->update($this->getTableName())
            ->set('field_order', ':fieldOrder')
            ->set('field_name', ':fieldName')
            ->set('field_label', ':fieldLabel')
            ->set('field_type', ':fieldType')
            ->set('allow_blank', ':allowBlank')
            ->set('is_required_default', ':isRequiredDefault')
            ->set('active', ':active')
            ->set('show_always', ':showAlways')
            ->set('field_group', ':fieldGroup')
            ->set('show_in_overview', ':showInOverview')
            ->where('id = :id')
            ->setParameter('id', $data['id'])
            ->setParameter('fieldOrder', $data['field_order'])
            ->setParameter('fieldName', $data['field_name'])
            ->setParameter('fieldLabel', $data['field_label'])
            ->setParameter('fieldType', $data['field_type'])
            ->setParameter('allowBlank', $data['allow_blank'])
            ->setParameter('isRequiredDefault', $data['is_required_default'])
            ->setParameter('active', $data['active'])
            ->setParameter('showAlways', $data['show_always'])
            ->setParameter('fieldGroup', $data['field_group'])
            ->setParameter('showInOverview', $data['show_in_overview']);
    }
}