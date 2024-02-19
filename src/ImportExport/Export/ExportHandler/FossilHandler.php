<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
use App\ImportExport\ImportExportLimit;
use App\ImportExport\Types;
use App\Repository\FossilRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class FossilHandler extends AbstractExportHandler
{
    public function __construct(
        private readonly RequestStack  $requestStack,
        private readonly FossilRepository $fossilRepository,
    ) {
        parent::__construct($this->requestStack);
    }

    public function getKey(): string
    {
        return Types::TYPE_FOSSIL;
    }

    public function getColumnCount(): int
    {
        return $this->fossilRepository->getColumnCount();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getData(ExportStatus $status): array
    {
        return $this->fossilRepository->getExportList(ImportExportLimit::LIMIT, $status->getExported());
    }
}
