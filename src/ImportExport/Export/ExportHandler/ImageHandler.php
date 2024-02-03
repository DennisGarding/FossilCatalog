<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
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
        return 'image';
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
        return $this->imageRepository->getExportList(self::EXPORT_LIMIT, $status->getExported());
    }
}
