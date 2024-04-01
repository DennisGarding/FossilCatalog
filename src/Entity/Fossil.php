<?php

namespace App\Entity;

use App\Repository\FossilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FossilRepository::class)]
class Fossil implements \ArrayAccess, EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $showInGallery = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfDiscovery = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $foundInCountry = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $findingPlace = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coordinates = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $findingNotes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $formation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stratigraphicMember = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $stratigraphicNotes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $empire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tribe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $class = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $taxonomicOrder = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $family = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $genius = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $species = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subspecies = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $size = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $taxonomyNotes = null;

    /** @var array<mixed>|null */
    #[ORM\Column(nullable: true)]
    private ?array $extraFields = null;

    #[ORM\ManyToMany(targetEntity: Category::class)]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    private Collection $tags;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Image::class, cascade: ['persist'])]
    private Collection $images;

    #[ORM\ManyToOne]
    private ?EarthAgeSystem $eaSystem = null;

    #[ORM\ManyToOne]
    private ?EarthAgeSeries $eaSeries = null;

    #[ORM\ManyToOne]
    private ?EarthAgeStage $eaStage = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isShowInGallery(): ?bool
    {
        return $this->showInGallery;
    }

    public function setShowInGallery(bool $showInGallery): static
    {
        $this->showInGallery = $showInGallery;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getDateOfDiscovery(): ?\DateTimeInterface
    {
        return $this->dateOfDiscovery;
    }

    public function setDateOfDiscovery(?\DateTimeInterface $dateOfDiscovery): static
    {
        $this->dateOfDiscovery = $dateOfDiscovery;

        return $this;
    }

    public function getFoundInCountry(): ?string
    {
        return $this->foundInCountry;
    }

    public function setFoundInCountry(?string $foundInCountry): static
    {
        $this->foundInCountry = $foundInCountry;

        return $this;
    }

    public function getFindingPlace(): ?string
    {
        return $this->findingPlace;
    }

    public function setFindingPlace(?string $findingPlace): static
    {
        $this->findingPlace = $findingPlace;

        return $this;
    }

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(?string $coordinates): static
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getFindingNotes(): ?string
    {
        return $this->findingNotes;
    }

    public function setFindingNotes(string $findingNotes): static
    {
        $this->findingNotes = $findingNotes;

        return $this;
    }

    public function getFormation(): ?string
    {
        return $this->formation;
    }

    public function setFormation(?string $formation): static
    {
        $this->formation = $formation;

        return $this;
    }

    public function getStratigraphicMember(): ?string
    {
        return $this->stratigraphicMember;
    }

    public function setStratigraphicMember(?string $stratigraphicMember): static
    {
        $this->stratigraphicMember = $stratigraphicMember;

        return $this;
    }

    public function getStratigraphicNotes(): ?string
    {
        return $this->stratigraphicNotes;
    }

    public function setStratigraphicNotes(?string $stratigraphicNotes): static
    {
        $this->stratigraphicNotes = $stratigraphicNotes;

        return $this;
    }

    public function getEmpire(): ?string
    {
        return $this->empire;
    }

    public function setEmpire(?string $empire): static
    {
        $this->empire = $empire;

        return $this;
    }

    public function getTribe(): ?string
    {
        return $this->tribe;
    }

    public function setTribe(?string $tribe): static
    {
        $this->tribe = $tribe;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): static
    {
        $this->class = $class;

        return $this;
    }

    public function getTaxonomicOrder(): ?string
    {
        return $this->taxonomicOrder;
    }

    public function setTaxonomicOrder(?string $taxonomicOrder): static
    {
        $this->taxonomicOrder = $taxonomicOrder;

        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(?string $family): static
    {
        $this->family = $family;

        return $this;
    }

    public function getGenius(): ?string
    {
        return $this->genius;
    }

    public function setGenius(?string $genius): static
    {
        $this->genius = $genius;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(?string $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function getSubspecies(): ?string
    {
        return $this->subspecies;
    }

    public function setSubspecies(?string $subspecies): static
    {
        $this->subspecies = $subspecies;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getTaxonomyNotes(): ?string
    {
        return $this->taxonomyNotes;
    }

    public function setTaxonomyNotes(?string $taxonomyNotes): static
    {
        $this->taxonomyNotes = $taxonomyNotes;

        return $this;
    }

    /**
     * @return array<int|string, mixed>|null
     */
    public function getExtraFields(): ?array
    {
        return $this->extraFields;
    }

    /**
     * @param array<mixed>|null $extraFields
     */
    public function setExtraFields(?array $extraFields): static
    {
        $this->extraFields = $extraFields;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @param array<string, mixed> $fossilData
     */
    public function apply(array $fossilData): void
    {
        $this->tags = new ArrayCollection();
        $this->categories = new ArrayCollection();

        foreach ($fossilData as $key => $value) {
            if ($key === 'tags') {
                foreach ($value as $tag) {
                    $this->addTag($tag);
                }
            } elseif ($key === 'categories') {
                foreach ($value as $category) {
                    $this->addCategory($category);
                }
            } elseif ($key === 'images') {
                foreach ($value as $image) {
                    $this->addImage($image);
                }
            } else {
                $this->$key = $value;
            }
        }
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

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        $this->images->removeElement($image);

        return $this;
    }

    public function getEaSystem(): ?EarthAgeSystem
    {
        return $this->eaSystem;
    }

    public function setEaSystem(?EarthAgeSystem $eaSystem): static
    {
        $this->eaSystem = $eaSystem;

        return $this;
    }

    public function getEaSeries(): ?EarthAgeSeries
    {
        return $this->eaSeries;
    }

    public function setEaSeries(?EarthAgeSeries $eaSeries): static
    {
        $this->eaSeries = $eaSeries;

        return $this;
    }

    public function getEaStage(): ?EarthAgeStage
    {
        return $this->eaStage;
    }

    public function setEaStage(?EarthAgeStage $eaStage): static
    {
        $this->eaStage = $eaStage;

        return $this;
    }
}
