<?php

namespace App\Images\ThumbnailGenerator;

use App\Images\ThumbnailGenerator\Handler\JpegHandler;
use App\Images\ThumbnailGenerator\Handler\PngHandler;
use App\Images\ThumbnailGenerator\Handler\ThumbnailCreationHandlerInterface;

class ThumbnailGenerator implements ThumbnailGeneratorInterface
{
    /**
     * @var array<int, ThumbnailCreationHandlerInterface>
     */
    private array $handler = [];

    public function __construct()
    {
        $this->addHandler(new JpegHandler());
        $this->addHandler(new PngHandler());
    }

    public function addHandler(ThumbnailCreationHandlerInterface $handler): void
    {
        $this->handler[] = $handler;
    }

    public function generate(string $root, string $imagePath, string $thumbnailTargetPath, string $mimeType): void
    {
        $imagePath = sprintf('%s/%s', $root, $imagePath);
        $thumbnailTargetPath = sprintf('%s/%s', $root, $thumbnailTargetPath);

        $handler = $this->getHandler($mimeType);

        $handler->create($imagePath, $thumbnailTargetPath);
    }

    private function getHandler(string $mimeType): ThumbnailCreationHandlerInterface
    {
        foreach ($this->handler as $thumbnailGenerator) {
            if ($thumbnailGenerator->supports($mimeType)) {
                return $thumbnailGenerator;
            }
        }

        throw new ThumbnailGeneratorHandlerNotFoundException($mimeType);
    }
}