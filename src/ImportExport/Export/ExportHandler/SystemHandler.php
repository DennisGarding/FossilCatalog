<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
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
        return 'system';
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
        return $this->systemRepository->getExportList(self::EXPORT_LIMIT, $status->getExported());
    }
}
