<?php

namespace App\Images;

use App\Entity\Fossil;
use App\Entity\Image;
use App\Entity\Settings;
use App\Images\ThumbnailGenerator\ThumbnailGeneratorInterface;
use App\Repository\ImageRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploadService
{
    public const BASE_PATH = 'images/gallery';
    public const PATH_TEMPLATE = '%s/%s';
    private const ALGORITHM = 'sha256';
    private const CHUNK_STRING_LENGTH = 5;
    private const ARRAY_SLICE_OFFSET = 0;
    private const ARRAY_SLICE_LENGTH = 3;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $rootDirectory,
        private readonly ThumbnailGeneratorInterface $thumbnailGenerator,
        private readonly ImageRepository $imageRepository,
    ) {}

    /**
     * @return array<Image>
     */
    public function uploadFossilImages(Fossil $fossil): array
    {
        $uploadedFiles = $this->getFossilUploadedImages();
        $images = [];
        foreach ($uploadedFiles as $uploadedFile) {
            $path = $this->createHashedPath($uploadedFile);

            $image = new Image();
            $image->setName($uploadedFile->getClientOriginalName());
            $image->setPath(sprintf(self::PATH_TEMPLATE, $path, $image->getName()));
            $image->setThumbnailPath(sprintf(self::PATH_TEMPLATE, $path, 'thumbnail_' . $image->getName()));
            $image->setMimeType($uploadedFile->getMimeType());
            $image->setShowInGallery(false);
            if ($fossil->getImages()->isEmpty()) {
                $image->setIsMainImage(true);
            } else {
                $image->setIsMainImage(false);
            }

            $uploadedFile->move(
                sprintf('%s/%s/%s', $this->rootDirectory, 'public', $path),
                $image->getName()
            );

            $images[] = $image;
            $fossil->addImage($image);

            $this->thumbnailGenerator->generate(
                $this->rootDirectory,
                'public/' . $image->getPath(),
                'public/' . $image->getThumbnailPath(),
                $image->getMimeType()
            );
        }

        return $images;
    }

    public function uploadSettingsBanner(Settings $settings): void
    {
        $uploadedFile = $this->getUploadedBanner();
        if (!$uploadedFile instanceof UploadedFile) {
            return;
        }

        $path = $this->createHashedPath($uploadedFile);

        $image = new Image();
        $image->setName($uploadedFile->getClientOriginalName());
        $image->setPath(sprintf(self::PATH_TEMPLATE, $path, $image->getName()));
        $image->setThumbnailPath(sprintf(self::PATH_TEMPLATE, $path, 'thumbnail_' . $image->getName()));
        $image->setMimeType($uploadedFile->getMimeType());
        $image->setShowInGallery(false);
        $image->setIsMainImage(false);

        $uploadedFile->move(
            sprintf('%s/%s/%s', $this->rootDirectory, 'public', $path),
            $image->getName()
        );

        if ($settings->getBanner() instanceof Image) {
            $this->imageRepository->delete($settings->getBanner());
        }

        $settings->setBanner($image);

        $this->thumbnailGenerator->generate(
            $this->rootDirectory,
            'public/' . $image->getPath(),
            'public/' . $image->getThumbnailPath(),
            $image->getMimeType()
        );
    }

    private function createHashedPath(UploadedFile $uploadedFile): string
    {
        $imageHash = hash_file(self::ALGORITHM, $uploadedFile->getRealPath());
        if (!$imageHash) {
            throw new \RuntimeException('Cannot create Image hash');
        }

        $chunks = array_slice(explode(PHP_EOL, chunk_split($imageHash, self::CHUNK_STRING_LENGTH, PHP_EOL)), self::ARRAY_SLICE_OFFSET, self::ARRAY_SLICE_LENGTH);

        return sprintf(self::PATH_TEMPLATE, self::BASE_PATH, implode('/', $chunks));
    }

    private function getUploadedBanner(): ?UploadedFile
    {
        $file = $_FILES['banner'] ?? [];
        if ($file['error']) {
            return null;
        }

        return new UploadedFile(
            $file['tmp_name'],
            $file['name'],
            $file['type'],
            $file['error']
        );
    }

    /**
     * @return array<UploadedFile>
     */
    private function getFossilUploadedImages(): array
    {
        $files = $_FILES['images'] ?? [];
        $uploadedFiles = [];

        foreach ($files['name'] as $index => $file) {
            if (empty($file)) {
                continue;
            }

            $uploadedFiles[] = new UploadedFile(
                $files['tmp_name'][$index],
                $file,
                $files['type'][$index],
                $files['error'][$index]
            );
        }

        return $uploadedFiles;
    }
}
