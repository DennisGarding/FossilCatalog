<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
use App\ImportExport\ImportExportLimit;
use App\ImportExport\Types;
use App\Repository\EarthAgeSystemRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class SystemHandler extends AbstractExportHandler
{
    public function __construct(
        private readonly RequestStack  $requestStack,
        private readonly EarthAgeSystemRepository $systemRepository,
    ) {
        parent::__construct($this->requestStack);
    }

    public function getKey(): string
    {
        return Types::TYPE_SYSTEM;
    }

    public function getColumnCount(): int
    {
        return $this->systemRepository->getColumnCount();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getData(ExportStatus $status): array
    {
        return $this->systemRepository->getExportList(ImportExportLimit::LIMIT, $status->getExported());
    }
}
