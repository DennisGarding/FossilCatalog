<?php

namespace App\Entity;

use App\Repository\VisitorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitorRepository::class)]
class Visitor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $longTermKey = null;

    #[ORM\Column(length: 255)]
    private ?string $shortTermKey = null;

    #[ORM\Column]
    private ?int $visits = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLongTermKey(): ?string
    {
        return $this->longTermKey;
    }

    public function setLongTermKey(string $longTermKey): static
    {
        $this->longTermKey = $longTermKey;

        return $this;
    }

    public function getShortTermKey(): ?string
    {
        return $this->shortTermKey;
    }

    public function setShortTermKey(string $shortTermKey): static
    {
        $this->shortTermKey = $shortTermKey;

        return $this;
    }

    public function getVisits(): ?int
    {
        return $this->visits;
    }

    public function setVisits(int $visits): static
    {
        $this->visits = $visits;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function increment(string $shortTermKey): void
    {
        $this->shortTermKey = $shortTermKey;
        $this->visits++;
        $this->updatedAt = new \DateTimeImmutable();
    }
}
