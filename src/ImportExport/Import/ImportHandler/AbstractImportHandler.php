<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\FileTypes;
use App\ImportExport\Import\ImportStatus;
use App\ImportExport\Import\SessionTrait;
use App\ImportExport\Import\TableConfig;
use App\ImportExport\ImportExportLimit;
use App\ImportExport\MissingArrayKeyException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractImportHandler
{
    protected ImportStatus $status;

    public function __construct(
        protected RequestStack $requestStack,
        protected Connection   $connection,
    ) {}

    abstract public function getKey(): string;

    abstract protected function getTableName(): string;

    /**
     * @param array<string, mixed> $data
     */
    abstract public function createQuery(array $data): ?QueryBuilder;

    public function analyzeData(string $directory): ImportStatus
    {
        $sourceFile = $this->createFullPath($directory, $this->getFileName());

        $toImport = $this->getNumberOfLines($sourceFile);

        $this->status = new ImportStatus($this->getKey(), $directory, $sourceFile, $toImport);

        $this->saveSession();

        return $this->status;
    }

    public function import(): ImportStatus
    {
        $this->status = $this->getStatusFromSession();

        $fileString = $this->status->getFile();
        if ($fileString === null) {
            $this->status->finish();
            $this->saveSession();

            return $this->status;
        }

        $file = fopen($this->status->getFile(), 'rb');

        $offset = $this->status->getImported();
        for ($i = 0; $i < $offset; $i++) {
            fgets($file);
        }

        $lineCounter = 0;
        for ($i = 0; $i < ImportExportLimit::LIMIT; $i++) {
            $line = fgets($file);
            if (empty($line)) {
                break;
            }

            $array = json_decode($line, true);
            if (!is_array($array)) {
                throw new \UnexpectedValueException('Expect array got ' . gettype($array));
            }

            $query = $this->createQuery($array);
            if ($query !== null) {
                $query->executeQuery();
            }

            if ($this instanceof AdditionalWorkerInterface) {
                $this->doWork($array, $this->status);
            }

            $lineCounter++;
        }

        fclose($file);

        $this->status->add($lineCounter);

        if ($this->status->getImported() >= $this->status->getInImportQueue()) {
            $this->status->finish();
        }

        $this->saveSession();

        return $this->status;
    }

    public function getStatus(): ImportStatus
    {
        return $this->getStatusFromSession();
    }

    /**
     * @return ImportStatus
     *
     * @throws MissingArrayKeyException
     */
    protected function getStatusFromSession(): ImportStatus
    {
        $array = $this->requestStack->getSession()->get($this->getKey());
        if (!is_array($array)) {
            throw new \UnexpectedValueException('Expect array got ' . gettype($array));
        }

        return (new ImportStatus($this->getKey()))->fromArray($array);
    }

    protected function saveSession(): void
    {
        $this->requestStack->getSession()->set($this->getKey(), $this->status->toArray());
    }

    public function clearSession(): void
    {
        $this->requestStack->getSession()->remove($this->getKey());
    }

    protected function createFullPath(string $directory, string $fileName): string
    {
        return sprintf('%s/%s', $directory, $fileName);
    }

    protected function getFileName(): string
    {
        return $this->getKey() . FileTypes::FOSSIL_CATALOG_BACKUP;
    }

    protected function getNumberOfLines(string $importFile): int
    {
        $lineCount = 0;
        $file = fopen($importFile, 'rb');
        if (!$file) {
            return $lineCount;
        }

        while (!feof($file)) {
            $line = fgets($file);
            if (!$line) {
                break;
            }

            $lineCount++;
        }

        fclose($file);

        return $lineCount;
    }

    protected function dataSetExists(TableConfig $config): bool
    {
        $query = $this->connection->createQueryBuilder();

        if ($config->getType() === TableConfig::TYPE_DATA) {
            return $query->select('id')
                    ->from($config->getTableName())
                    ->where('id = :id')
                    ->setParameter('id', $config->getIdOne())
                    ->executeQuery()
                    ->fetchOne() !== false;
        }

        return $query->select([$config->getColumnOneName(), $config->getColumnTwoName()])
                ->from($config->getTableName())
                ->where($config->getColumnOneName() . ' = :idOne')
                ->andWhere($config->getColumnTwoName() . ' = :idTwo')
                ->setParameter('idOne', $config->getIdOne())
                ->setParameter('idTwo', $config->getIdTwo())
                ->executeQuery()
                ->fetchOne() !== false;
    }
}