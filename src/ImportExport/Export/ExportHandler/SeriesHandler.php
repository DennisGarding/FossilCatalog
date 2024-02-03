<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
use App\Repository\EarthAgeSeriesRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class SeriesHandler extends AbstractExportHandler
{
    public function __construct(
        private readonly RequestStack  $requestStack,
        private readonly EarthAgeSeriesRepository $seriesRepository,
    ) {
        parent::__construct($this->requestStack);
    }

    public function getKey(): string
    {
        return 'series';
    }

    public function getColumnCount(): int
    {
        return $this->seriesRepository->getColumnCount();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getData(ExportStatus $status): array
    {
        return $this->seriesRepository->getExportList(self::EXPORT_LIMIT, $status->getExported());
    }
}
