<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
use App\ImportExport\Types;
use App\Repository\SettingsRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class SettingsHandler extends AbstractExportHandler
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly SettingsRepository $settingsRepository,
    ) {
        parent::__construct($this->requestStack);
    }

    public function getKey(): string
    {
        return Types::TYPE_SETTINGS;
    }

    public function getColumnCount(): int
    {
        return $this->settingsRepository->getColumnCount();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getData(ExportStatus $status): array
    {
        return $this->settingsRepository->getExportList();
    }
}
