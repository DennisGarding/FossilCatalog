<?php

namespace App\Entity;

use App\Repository\EarthAgeStageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EarthAgeStageRepository::class)]
class EarthAgeStage implements \ArrayAccess, EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $earthAgeSeriesId = null;

    #[ORM\ManyToOne(inversedBy: 'earthAgeStage')]
    private ?EarthAgeSeries $earthAgeSeries = null;

    #[ORM\Column]
    private ?bool $custom = null;

    public function __construct()
    {
    }

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

    public function offsetExists(mixed $offset): bool
    {
        if (!property_exists($this, $offset)) {
            return false;
        }

        return true;
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->$offset;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->$offset = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->$offset = null;
    }

    public function getEarthAgeSeries(): ?EarthAgeSeries
    {
        return $this->earthAgeSeries;
    }

    public function setEarthAgeSeries(?EarthAgeSeries $earthAgeSeries): static
    {
        $this->earthAgeSeries = $earthAgeSeries;

        return $this;
    }

    public function isCustom(): ?bool
    {
        return $this->custom;
    }

    public function setCustom(bool $custom): static
    {
        $this->custom = $custom;

        return $this;
    }
}
