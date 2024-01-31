<?php

namespace App\Images\ThumbnailGenerator;

use App\Images\ThumbnailGenerator\Handler\ThumbnailCreationHandlerInterface;

interface ThumbnailGeneratorInterface
{
    public function addHandler(ThumbnailCreationHandlerInterface $handler): void;

    public function generate(string $root, string $imagePath, string $thumbnailTargetPath, string $mimeType): void;
}