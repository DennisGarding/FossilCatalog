<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
use App\ImportExport\ImportExportLimit;
use App\ImportExport\Types;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class ImageHandler extends AbstractExportHandler
{
    public function __construct(
        private readonly RequestStack  $requestStack,
        private readonly ImageRepository $imageRepository,
    ) {
        parent::__construct($this->requestStack);
    }

    public function getKey(): string
    {
        return Types::TYPE_IMAGE;
    }

    public function getColumnCount(): int
    {
        return $this->imageRepository->getColumnCount();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getData(ExportStatus $status): array
    {
        return $this->imageRepository->getExportList(ImportExportLimit::LIMIT, $status->getExported());
    }
}
