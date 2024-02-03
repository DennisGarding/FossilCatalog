<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
use App\Repository\EarthAgeStageRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class StageHandler extends AbstractExportHandler
{
    public function __construct(
        private readonly RequestStack  $requestStack,
        private readonly EarthAgeStageRepository $stageRepository,
    ) {
        parent::__construct($this->requestStack);
    }

    public function getKey(): string
    {
        return 'stage';
    }

    public function getColumnCount(): int
    {
        return $this->stageRepository->getColumnCount();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getData(ExportStatus $status): array
    {
        return $this->stageRepository->getExportList(self::EXPORT_LIMIT, $status->getExported());
    }
}
