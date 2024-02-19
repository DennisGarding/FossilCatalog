<?php

namespace App\ImportExport\Import;

use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

class CopyImageService
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string     $rootDirectory,
        private readonly Connection $connection
    ) {}

    public function copyImages(array $data, string $basePath)
    {
        $filesystem = new Filesystem();

        $path = $data['path'];
        $thumbnailPath = $data['thumbnail_path'];

        $imageSourcePath = $basePath . '/' . str_replace('images/gallery/', '', $path);
        $thumbnailSourcePath = $basePath . '/' . str_replace('images/gallery/', '', $thumbnailPath);

        $imageDestinationPath = $this->rootDirectory . '/public/' . $path;
        $thumbnailDestinationPath = $this->rootDirectory . '/public/' . $thumbnailPath;

        if ($filesystem->exists($imageSourcePath)) {
            $filesystem->copy($imageSourcePath, $imageDestinationPath);
        }

        if ($filesystem->exists($thumbnailSourcePath)) {
            $filesystem->copy($thumbnailSourcePath, $thumbnailDestinationPath);
        }
    }


}