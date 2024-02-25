<?php

namespace App\Images\ThumbnailGenerator\Handler;

interface ThumbnailCreationHandlerInterface
{
    public const THUMBNAIL_WIDTH = 400;

    public const MIMETYPE_JPG = 'image/jpeg';

    public const MIMETYPE_PNG = 'image/png';

    public function supports(string $mimeType): bool;

    public function create(string $imageSourcePath, string $thumbnailTargetPath): void;
}
