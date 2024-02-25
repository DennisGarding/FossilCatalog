<?php

namespace App\Entity;

use App\Repository\EarthAgeSeriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EarthAgeSeriesRepository::class)]
class EarthAgeSeries implements \ArrayAccess, EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $earthAgeSystemId = null;

    #[ORM\ManyToOne(inversedBy: 'earthAgeSeries')]
    private ?EarthAgeSystem $earthAgeSystem = null;

    #[ORM\OneToMany(mappedBy: 'earthAgeSeries', targetEntity: EarthAgeStage::class)]
    private Collection $earthAgeStage;

    #[ORM\Column]
    private ?bool $custom = null;

    public function __construct()
    {
        $this->earthAgeStage = new ArrayCollection();
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

    public function getEarthAgeSystem(): ?EarthAgeSystem
    {
        return $this->earthAgeSystem;
    }

    public function setEarthAgeSystem(?EarthAgeSystem $earthAgeSystem): static
    {
        $this->earthAgeSystem = $earthAgeSystem;

        return $this;
    }

    /**
     * @return Collection<int, EarthAgeStage>
     */
    public function getEarthAgeStage(): Collection
    {
        return $this->earthAgeStage;
    }

    public function addEarthAgeStage(EarthAgeStage $earthAgeStage): static
    {
        if (!$this->earthAgeStage->contains($earthAgeStage)) {
            $this->earthAgeStage->add($earthAgeStage);
            $earthAgeStage->setEarthAgeSeries($this);
        }

        return $this;
    }

    public function removeEarthAgeStage(EarthAgeStage $earthAgeStage): static
    {
        if ($this->earthAgeStage->removeElement($earthAgeStage)) {
            // set the owning side to null (unless already changed)
            if ($earthAgeStage->getEarthAgeSeries() === $this) {
                $earthAgeStage->setEarthAgeSeries(null);
            }
        }

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
