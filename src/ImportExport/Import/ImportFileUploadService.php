<?php

namespace App\ImportExport\Import;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportFileUploadService
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $rootDirectory,
    ) {}

    public function upload(UploadedFile $importFile): void
    {
        $importFile->move(sprintf('%s/%s', $this->rootDirectory, 'public/import'), $importFile->getClientOriginalName());
    }
}