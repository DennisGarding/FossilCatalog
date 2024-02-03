<?php

namespace App\ImportExport\Export;

use App\ImportExport\CreateZipException;
use App\ImportExport\Export\ExportHandler\AbstractExportHandler;
use App\ImportExport\Export\ExportHandler\CategoryHandler;
use App\ImportExport\Export\ExportHandler\CategoryRelationHandler;
use App\ImportExport\Export\ExportHandler\SeriesHandler;
use App\ImportExport\Export\ExportHandler\SystemHandler;
use App\ImportExport\Export\ExportHandler\TagHandler;
use App\ImportExport\Export\ExportHandler\TagRelationHandler;
use App\Repository\ExportRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use ZipArchive;

class ExportService implements ExportServiceInterface
{
    private const TARGET_DIRECTORY_SESSION_KEY = 'exportTargetDirectory';

    private const ZIP_FILE_EXTENSION = '.fc.backup.zip';

    /**
     * @var array<AbstractExportHandler>
     */
    private array $handler;

    private string $targetDirectory;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string                  $rootDirectory,
        private readonly RequestStack            $requestStack,
        private readonly ExportRepository        $exportRepository,
        private readonly CategoryHandler         $categoryHandler,
        private readonly CategoryRelationHandler $categoryRelationHandler,
        private readonly TagHandler              $tagHandler,
        private readonly TagRelationHandler      $tagRelationHandler,
        private readonly SystemHandler           $systemHandler,
        private readonly SeriesHandler           $seriesHandler,
    ) {
        $this->handler = [
            $this->categoryHandler,
            $this->categoryRelationHandler,
            $this->tagHandler,
            $this->tagRelationHandler,
            $this->systemHandler,
            $this->seriesHandler,
        ];
    }

    public function analyzeData(): array
    {
        $status = [];
        /** @var AbstractExportHandler $exportHandler */
        foreach ($this->handler as $exportHandler) {
            $status[$exportHandler->getKey()] = $exportHandler->analyzeData()->toArray();
        }

        return $status;
    }

    public function initializeFiles(): void
    {
        $this->targetDirectory = $this->createTargetDirectory();
        $this->createLockFile($this->targetDirectory);

        /** @var AbstractExportHandler $exportHandler */
        foreach ($this->handler as $exportHandler) {
            $exportHandler->initialize($this->targetDirectory);
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function export(): array
    {
        $status = [];

        /** @var AbstractExportHandler $exportHandler */
        foreach ($this->handler as $exportHandler) {
            if ($exportHandler->getStatus()->isFinished()) {
                continue;
            }

            $status[$exportHandler->getKey()] = $exportHandler->export()->toArray();
        }

        return $status;
    }

    public function createZipFile(string $directory, string $name): string
    {
        $target = $this->createFileName($directory, $name . self::ZIP_FILE_EXTENSION);

        $zip = new ZipArchive();
        if ($zip->open($target, ZipArchive::CREATE) !== true) {
            throw new CreateZipException();
        }

        foreach ($this->handler as $handler) {
            $handlerFile = $this->createFileName($directory, $handler->getFileName());
            if (!is_file($handlerFile)) {
                continue;
            }

            $zip->addFile($handlerFile, $handler->getFileName());
        }

        $zip->close();

        return $target;
    }

    public function clearSession(): void
    {
        /** @var AbstractExportHandler $exportHandler */
        foreach ($this->handler as $exportHandler) {
            $exportHandler->clearSession();
        }

        $this->targetDirectory = $this->createTargetDirectory();
        unlink($this->createLockFile($this->targetDirectory));
        $session = $this->requestStack->getSession();
        $session->remove(md5($this->targetDirectory));
        $session->remove(self::TARGET_DIRECTORY_SESSION_KEY);
    }

    public function deleteBackup(string $path): void
    {
        $this->exportRepository->delete($path);
    }

    private function createTargetDirectory(): string
    {
        $session = $this->requestStack->getSession();

        $targetDirectory = $session->get(self::TARGET_DIRECTORY_SESSION_KEY);
        if (is_string($targetDirectory)) {
            $this->createDirectory($targetDirectory);

            return $targetDirectory;
        }

        $targetDirectory = sprintf(
            '%s/%s/%s',
            $this->rootDirectory,
            'public/export',
            $this->getDateTimeString(),
        );

        $session->set(self::TARGET_DIRECTORY_SESSION_KEY, $targetDirectory);

        $this->createDirectory($targetDirectory);

        return $targetDirectory;
    }

    private function createDirectory(string $directory): void
    {
        if (is_dir($directory)) {
            return;
        }

        if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }
    }

    private function createLockFile(string $targetDirectory): string
    {
        $session = $this->requestStack->getSession();
        $lockFile = $session->get(md5($targetDirectory));
        if (is_string($lockFile)) {
            return $lockFile;
        }

        $lockFile = sprintf('%s/%s', $targetDirectory, 'in_progress.lock');

        $session->set(md5($targetDirectory), $lockFile);

        file_put_contents($lockFile, $this->getDateTimeString());

        return $lockFile;
    }

    private function getDateTimeString(): string
    {
        return (new \DateTime())->format('y-m-d h:i:s');
    }

    private function createFileName(string $path, string $name): string
    {
        return sprintf('%s/%s', $path, $name);
    }
}