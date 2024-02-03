<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CategoryRelationHandler extends AbstractExportHandler
{
    public function __construct(
        private readonly RequestStack       $requestStack,
        private readonly CategoryRepository $categoryRepository,
    ) {
        parent::__construct($this->requestStack);
    }

    public function getKey(): string
    {
        return 'category_relation';
    }

    public function analyzeData(): ExportStatus
    {
        $inExportQueue = $this->categoryRepository->getRelationColumnCount();

        $categoryStatus = new ExportStatus($this->getKey(), $inExportQueue);

        $this->saveSession($categoryStatus);

        return $categoryStatus;
    }

    public function export(): ExportStatus
    {
        $status = $this->getStatusFromSession();

        if ($status->isFinished()) {
            return $status;
        }

        $data = $this->categoryRepository->getRelationExportList(self::EXPORT_LIMIT, $status->getExported());

        foreach ($data as $line) {
            \file_put_contents($this->targetFile, json_encode($line) . PHP_EOL, FILE_APPEND);
        }

        $status->add(count($data));

        if ($status->getExported() >= $status->getInExportQueue()) {
            $status->finish();
        }

        $this->saveSession($status);

        return $status;
    }
}