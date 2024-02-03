<?php

namespace App\ImportExport\Export\ExportHandler;

use App\ImportExport\Export\ExportStatus;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class TagRelationHandler extends AbstractExportHandler
{
    public function __construct(
        private readonly RequestStack  $requestStack,
        private readonly TagRepository $tagRepository,
    ) {
        parent::__construct($this->requestStack);
    }

    public function getKey(): string
    {
        return 'tag_relation';
    }

    public function getColumnCount(): int
    {
        return $this->tagRepository->getRelationColumnCount();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getData(ExportStatus $status): array
    {
        return $this->tagRepository->getRelationExportList(self::EXPORT_LIMIT, $status->getExported());
    }
}