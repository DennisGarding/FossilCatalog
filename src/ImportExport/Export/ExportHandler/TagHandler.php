<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
use App\ImportExport\ImportExportLimit;
use App\ImportExport\Types;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class TagHandler extends AbstractExportHandler
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly TagRepository $tagRepository,
    ) {
        parent::__construct($this->requestStack);
    }

    public function getKey(): string
    {
        return Types::TYPE_TAG;
    }

    public function getColumnCount(): int
    {
        return $this->tagRepository->getColumnCount();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getData(ExportStatus $status): array
    {
        return $this->tagRepository->getExportList(ImportExportLimit::LIMIT, $status->getExported());
    }
}
