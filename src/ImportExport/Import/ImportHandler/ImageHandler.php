<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\CopyImageService;
use App\ImportExport\Import\ImportStatus;
use App\ImportExport\Import\TableConfig;
use App\ImportExport\Types;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;

class ImageHandler extends AbstractImportHandler implements AdditionalWorkerInterface
{
    public function __construct(
        protected RequestStack   $requestStack,
        protected Connection     $connection,
        private readonly CopyImageService $copyImageService
    ) {
        parent::__construct($requestStack, $connection);
    }

    public function getKey(): string
    {
        return Types::TYPE_IMAGE;
    }

    protected function getTableName(): string
    {
        return 'image';
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
                'path' => ':path',
                'thumbnail_path' => ':thumbnail_path',
                'show_in_gallery' => ':show_in_gallery',
                'is_main_image' => ':is_main_image',
                'mime_type' => ':mime_type',
            ])
            ->setParameter('id', $data['id'])
            ->setParameter('name', $data['name'])
            ->setParameter('path', $data['path'])
            ->setParameter('thumbnail_path', $data['thumbnail_path'])
            ->setParameter('show_in_gallery', $data['show_in_gallery'])
            ->setParameter('is_main_image', $data['is_main_image'])
            ->setParameter('mime_type', $data['mime_type']);
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
            ->set('path', ':path')
            ->set('thumbnail_path', ':thumbnail_path')
            ->set('show_in_gallery', ':show_in_gallery')
            ->set('is_main_image', ':is_main_image')
            ->set('mime_type', ':mime_type')
            ->setParameter('id', $data['id'])
            ->setParameter('name', $data['name'])
            ->setParameter('path', $data['path'])
            ->setParameter('thumbnail_path', $data['thumbnail_path'])
            ->setParameter('show_in_gallery', $data['show_in_gallery'])
            ->setParameter('is_main_image', $data['is_main_image'])
            ->setParameter('mime_type', $data['mime_type']);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function doWork(array $data, ImportStatus $status): void
    {
        $this->copyImageService->copyImages($data, $status->getPath());
    }
}