<?php

namespace App\Entity;

use App\Repository\EarthAgeSystemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EarthAgeSystemRepository::class)]
class EarthAgeSystem implements \ArrayAccess, EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\OneToMany(mappedBy: 'earthAgeSystem', targetEntity: EarthAgeSeries::class)]
    private Collection $earthAgeSeries;

    #[ORM\Column]
    private ?bool $custom = null;

    public function __construct()
    {
        $this->earthAgeSeries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
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

    /**
     * @return Collection<int, EarthAgeSeries>
     */
    public function getEarthAgeSeries(): Collection
    {
        return $this->earthAgeSeries;
    }

    public function addEarthAgeSeries(EarthAgeSeries $earthAgeSeries): static
    {
        if (!$this->earthAgeSeries->contains($earthAgeSeries)) {
            $this->earthAgeSeries->add($earthAgeSeries);
            $earthAgeSeries->setEarthAgeSystem($this);
        }

        return $this;
    }

    public function removeEarthAgeSeries(EarthAgeSeries $earthAgeSeries): static
    {
        if ($this->earthAgeSeries->removeElement($earthAgeSeries)) {
            // set the owning side to null (unless already changed)
            if ($earthAgeSeries->getEarthAgeSystem() === $this) {
                $earthAgeSeries->setEarthAgeSystem(null);
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
