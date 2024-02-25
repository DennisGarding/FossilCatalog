<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
use App\ImportExport\ImportExportLimit;
use App\ImportExport\Types;
use App\Repository\FossilFormFieldRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class FormFieldHandler extends AbstractExportHandler
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly FossilFormFieldRepository $formFieldRepository,
    ) {
        parent::__construct($this->requestStack);
    }

    public function getKey(): string
    {
        return Types::TYPE_FORM_FIELD;
    }

    public function getColumnCount(): int
    {
        return $this->formFieldRepository->getColumnCount();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getData(ExportStatus $status): array
    {
        return $this->formFieldRepository->getExportList(ImportExportLimit::LIMIT, $status->getExported());
    }
}
