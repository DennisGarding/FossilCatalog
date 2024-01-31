<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    private ?string $thumbnailPath = null;

    #[ORM\Column]
    private ?bool $showInGallery = null;

    #[ORM\Column]
    private ?bool $isMainImage = null;

    #[ORM\Column(length: 255)]
    private ?string $mimeType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getThumbnailPath(): ?string
    {
        return $this->thumbnailPath;
    }

    public function setThumbnailPath(string $thumbnailPath): void
    {
        $this->thumbnailPath = $thumbnailPath;
    }

    public function isShowInGallery(): ?bool
    {
        return $this->showInGallery;
    }

    public function setShowInGallery(bool $showInGallery): void
    {
        $this->showInGallery = $showInGallery;
    }

    public function isIsMainImage(): ?bool
    {
        return $this->isMainImage;
    }

    public function setIsMainImage(bool $isMainImage): void
    {
        $this->isMainImage = $isMainImage;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }
}
