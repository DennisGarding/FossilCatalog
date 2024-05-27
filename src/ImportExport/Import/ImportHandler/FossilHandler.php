<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\TableConfig;
use App\ImportExport\Types;
use Doctrine\DBAL\Query\QueryBuilder;

class FossilHandler extends AbstractImportHandler
{
    public function getKey(): string
    {
        return Types::TYPE_FOSSIL;
    }

    protected function getTableName(): string
    {
        return 'fossil';
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
                'show_in_gallery' => ':show_in_gallery',
                'number' => ':number',
                'date_of_discovery' => ':date_of_discovery',
                'found_in_country' => ':found_in_country',
                'finding_place' => ':finding_place',
                'coordinates' => ':coordinates',
                'finding_notes' => ':finding_notes',
                'formation' => ':formation',
                'stratigraphic_member' => ':stratigraphic_member',
                'stratigraphic_notes' => ':stratigraphic_notes',
                'empire' => ':empire',
                'tribe' => ':tribe',
                'class' => ':class',
                'taxonomic_order' => ':taxonomic_order',
                'family' => ':family',
                'genus' => ':genus',
                'species' => ':species',
                'subspecies' => ':subspecies',
                'size' => ':size',
                'taxonomy_notes' => ':taxonomy_notes',
                'extra_fields' => ':extra_fields',
                'ea_system_id' => ':ea_system_id',
                'ea_series_id' => ':ea_series_id',
                'ea_stage_id' => ':ea_stage_id',
                'created_at' => ':created_at',
                'updated_at' => ':updated_at',
            ])
            ->setParameter('id', $data['id'])
            ->setParameter('show_in_gallery', $data['show_in_gallery'])
            ->setParameter('number', $data['number'])
            ->setParameter('date_of_discovery', $data['date_of_discovery'])
            ->setParameter('found_in_country', $data['found_in_country'])
            ->setParameter('finding_place', $data['finding_place'])
            ->setParameter('coordinates', $data['coordinates'])
            ->setParameter('finding_notes', $data['finding_notes'])
            ->setParameter('formation', $data['formation'])
            ->setParameter('stratigraphic_member', $data['stratigraphic_member'])
            ->setParameter('stratigraphic_notes', $data['stratigraphic_notes'])
            ->setParameter('empire', $data['empire'])
            ->setParameter('tribe', $data['tribe'])
            ->setParameter('class', $data['class'])
            ->setParameter('taxonomic_order', $data['taxonomic_order'])
            ->setParameter('family', $data['family'])
            ->setParameter('genus', $data['genus'])
            ->setParameter('species', $data['species'])
            ->setParameter('subspecies', $data['subspecies'])
            ->setParameter('size', $data['size'])
            ->setParameter('taxonomy_notes', $data['taxonomy_notes'])
            ->setParameter('extra_fields', $data['extra_fields'])
            ->setParameter('ea_system_id', $data['ea_system_id'])
            ->setParameter('ea_series_id', $data['ea_series_id'])
            ->setParameter('ea_stage_id', $data['ea_stage_id'])
            ->setParameter('created_at', $data['created_at'])
            ->setParameter('updated_at', $data['updated_at']);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function createUpdateQuery(array $data): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->update($this->getTableName())
            ->set('show_in_gallery', ':show_in_gallery')
            ->set('number', ':number')
            ->set('date_of_discovery', ':date_of_discovery')
            ->set('found_in_country', ':found_in_country')
            ->set('finding_place', ':finding_place')
            ->set('coordinates', ':coordinates')
            ->set('finding_notes', ':finding_notes')
            ->set('formation', ':formation')
            ->set('stratigraphic_member', ':stratigraphic_member')
            ->set('stratigraphic_notes', ':stratigraphic_notes')
            ->set('empire', ':empire')
            ->set('tribe', ':tribe')
            ->set('class', ':class')
            ->set('taxonomic_order', ':taxonomic_order')
            ->set('family', ':family')
            ->set('genus', ':genus')
            ->set('species', ':species')
            ->set('subspecies', ':subspecies')
            ->set('size', ':size')
            ->set('taxonomy_notes', ':taxonomy_notes')
            ->set('extra_fields', ':extra_fields')
            ->set('ea_system_id', ':ea_system_id')
            ->set('ea_series_id', ':ea_series_id')
            ->set('ea_stage_id', ':ea_stage_id')
            ->set('created_at', ':created_at')
            ->set('updated_at', ':updated_at')
            ->where('id = :id')
            ->setParameter('id', $data['id'])
            ->setParameter('show_in_gallery', $data['show_in_gallery'])
            ->setParameter('number', $data['number'])
            ->setParameter('date_of_discovery', $data['date_of_discovery'])
            ->setParameter('found_in_country', $data['found_in_country'])
            ->setParameter('finding_place', $data['finding_place'])
            ->setParameter('coordinates', $data['coordinates'])
            ->setParameter('finding_notes', $data['finding_notes'])
            ->setParameter('formation', $data['formation'])
            ->setParameter('stratigraphic_member', $data['stratigraphic_member'])
            ->setParameter('stratigraphic_notes', $data['stratigraphic_notes'])
            ->setParameter('empire', $data['empire'])
            ->setParameter('tribe', $data['tribe'])
            ->setParameter('class', $data['class'])
            ->setParameter('taxonomic_order', $data['taxonomic_order'])
            ->setParameter('family', $data['family'])
            ->setParameter('genus', $data['genus'])
            ->setParameter('species', $data['species'])
            ->setParameter('subspecies', $data['subspecies'])
            ->setParameter('size', $data['size'])
            ->setParameter('taxonomy_notes', $data['taxonomy_notes'])
            ->setParameter('extra_fields', $data['extra_fields'])
            ->setParameter('ea_system_id', $data['ea_system_id'])
            ->setParameter('ea_series_id', $data['ea_series_id'])
            ->setParameter('ea_stage_id', $data['ea_stage_id'])
            ->setParameter('created_at', $data['created_at'])
            ->setParameter('updated_at', $data['updated_at']);
    }
}
