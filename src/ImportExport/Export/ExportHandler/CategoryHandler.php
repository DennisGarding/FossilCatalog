<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
use App\ImportExport\ImportExportLimit;
use App\ImportExport\Types;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CategoryHandler extends AbstractExportHandler
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly CategoryRepository $categoryRepository,
    ) {
        parent::__construct($this->requestStack);
    }

    public function getKey(): string
    {
        return Types::TYPE_CATEGORY;
    }

    public function getColumnCount(): int
    {
        return $this->categoryRepository->getColumnCount();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getData(ExportStatus $status): array
    {
        return $this->categoryRepository->getExportList(ImportExportLimit::LIMIT, $status->getExported());
    }
}
