<?php

namespace App\Images\ThumbnailGenerator\Handler;

class JpegHandler implements ThumbnailCreationHandlerInterface
{
    public function supports(string $mimeType): bool
    {
        return $mimeType === self::MIMETYPE_JPG;
    }

    public function create(string $imageSourcePath, string $thumbnailTargetPath): void
    {
        $sourceImage = imagecreatefromjpeg($imageSourcePath);
        if (!$sourceImage instanceof \GdImage) {
            throw new \UnexpectedValueException(sprintf('$sourceImage: Cannot initiate GdImage for file %s', $imageSourcePath));
        }

        $orgWidth = imagesx($sourceImage);
        $orgHeight = imagesy($sourceImage);

        $thumbHeight = floor($orgHeight * (self::THUMBNAIL_WIDTH / $orgWidth));
        $destImage = imagecreatetruecolor(self::THUMBNAIL_WIDTH, (int) $thumbHeight);
        if (!$destImage instanceof \GdImage) {
            throw new \UnexpectedValueException(sprintf('$destImage: Cannot initiate GdImage for file %s', $imageSourcePath));
        }

        imagecopyresampled(
            $destImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            self::THUMBNAIL_WIDTH,
            (int) $thumbHeight,
            $orgWidth,
            $orgHeight
        );

        imagejpeg($destImage, $thumbnailTargetPath);
        imagedestroy($sourceImage);
        imagedestroy($destImage);
    }
}
