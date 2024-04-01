<?php

namespace App\ImportExport\Import;

use App\ImportExport\FileTypes;
use App\ImportExport\Import\ImportHandler\AbstractImportHandler;
use App\ImportExport\Import\ImportHandler\CategoryHandler;
use App\ImportExport\Import\ImportHandler\CategoryRelationHandler;
use App\ImportExport\Import\ImportHandler\FormFieldHandler;
use App\ImportExport\Import\ImportHandler\FossilHandler;
use App\ImportExport\Import\ImportHandler\ImageHandler;
use App\ImportExport\Import\ImportHandler\ImageRelationHandler;
use App\ImportExport\Import\ImportHandler\SeriesHandler;
use App\ImportExport\Import\ImportHandler\SettingsHandler;
use App\ImportExport\Import\ImportHandler\StageHandler;
use App\ImportExport\Import\ImportHandler\SystemHandler;
use App\ImportExport\Import\ImportHandler\TagHandler;
use App\ImportExport\Import\ImportHandler\TagRelationHandler;
use Doctrine\DBAL\Connection;
use Symfony\Component\Filesystem\Filesystem;

class ImportService
{
    /** @var array<AbstractImportHandler> */
    private array $handler;

    public function __construct(
        private readonly Connection $connection,
        private readonly CategoryHandler $categoryHandler,
        private readonly CategoryRelationHandler $categoryRelationHandler,
        private readonly TagHandler $tagHandler,
        private readonly TagRelationHandler $tagRelationHandler,
        private readonly FormFieldHandler $formFieldHandler,
        private readonly FossilHandler $fossilHandler,
        private readonly ImageHandler $imageHandler,
        private readonly ImageRelationHandler $imageRelationHandler,
        private readonly SystemHandler $systemHandler,
        private readonly SeriesHandler $seriesHandler,
        private readonly StageHandler $stageHandler,
        private readonly SettingsHandler $settingsHandler,
    ) {
        $this->handler = [
            $this->categoryHandler,
            $this->categoryRelationHandler,
            $this->tagHandler,
            $this->tagRelationHandler,
            $this->formFieldHandler,
            $this->fossilHandler,
            $this->imageHandler,
            $this->imageRelationHandler,
            $this->systemHandler,
            $this->seriesHandler,
            $this->stageHandler,
            $this->settingsHandler,
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function analyzeData(string $directory): array
    {
        $status = [];

        foreach ($this->handler as $importHandler) {
            $status[$importHandler->getKey()] = $importHandler->analyzeData($directory)->toArray();
        }

        return $status;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function import(): array
    {
        $status = [];

        try {
            $this->connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');

            foreach ($this->handler as $ImportHandler) {
                if ($ImportHandler->getStatus()->isFinished()) {
                    continue;
                }

                $status[$ImportHandler->getKey()] = $ImportHandler->import()->toArray();
            }
        } catch (\Throwable $exception) {
            throw new \RuntimeException($exception->getMessage(), 0, $exception);
        } finally {
            $this->connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
        }

        return $status;
    }

    public function clearSession(): void
    {
        foreach ($this->handler as $ImportHandler) {
            $ImportHandler->clearSession();
        }
    }

    public function extractZipFile(string $filePath): string
    {
        $extractTo = $this->createUnzipDirectory($filePath);

        $zipArchive = new \ZipArchive();
        if ($zipArchive->open($filePath) !== true) {
            $this->deleteFile($filePath);

            throw new \RuntimeException('Cannot open backup file');
        }

        $zipArchive->extractTo($extractTo);
        $zipArchive->close();

        return $extractTo;
    }

    private function createUnzipDirectory(string $filePath): string
    {
        $filesystem = new Filesystem();
        $directoryName = str_replace(FileTypes::ZIP_FILE_EXTENSION, '', $filePath);

        if ($filesystem->exists($directoryName)) {
            $filesystem->remove($directoryName);
        }

        $filesystem->mkdir($directoryName);

        return $directoryName;
    }

    private function deleteFile(string $filePath): void
    {
        unlink($filePath);
    }
}
