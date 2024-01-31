<?php

namespace App\Entity;

use App\Repository\FossilFormFieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FossilFormFieldRepository::class)]
class FossilFormField implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $fieldOrder = null;

    #[ORM\Column(length: 255)]
    private ?string $fieldName = null;

    #[ORM\Column(length: 255)]
    private ?string $fieldLabel = null;

    #[ORM\Column(length: 255)]
    private ?string $fieldType = null;

    #[ORM\Column]
    private ?bool $allowBlank = null;

    #[ORM\Column]
    private ?bool $isRequiredDefault = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?bool $showAlways = null;

    #[ORM\Column(length: 255)]
    private ?string $fieldGroup = null;

    #[ORM\Column]
    private ?bool $showInOverview = null;

    private mixed $fieldValue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFieldOrder(): ?int
    {
        return $this->fieldOrder;
    }

    public function setFieldOrder(int $fieldOrder): void
    {
        $this->fieldOrder = $fieldOrder;
    }

    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    public function setFieldName(string $fieldName): void
    {
        $this->fieldName = $fieldName;
    }

    public function getFieldLabel(): ?string
    {
        return $this->fieldLabel;
    }

    public function setFieldLabel(string $fieldLabel): void
    {
        $this->fieldLabel = $fieldLabel;
    }

    public function getFieldType(): ?string
    {
        return $this->fieldType;
    }

    public function setFieldType(string $fieldType): void
    {
        $this->fieldType = $fieldType;
    }

    public function isAllowBlank(): ?bool
    {
        return $this->allowBlank;
    }

    public function setAllowBlank(bool $allowBlank): void
    {
        $this->allowBlank = $allowBlank;
    }

    public function isIsRequiredDefault(): ?bool
    {
        return $this->isRequiredDefault;
    }

    public function setIsRequiredDefault(bool $isRequiredDefault): void
    {
        $this->isRequiredDefault = $isRequiredDefault;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function isShowAlways(): ?bool
    {
        return $this->showAlways;
    }

    public function setShowAlways(bool $showAlways): void
    {
        $this->showAlways = $showAlways;
    }

    public function getFieldGroup(): ?string
    {
        return $this->fieldGroup;
    }

    public function setFieldGroup(string $fieldGroup): void
    {
        $this->fieldGroup = $fieldGroup;
    }

    public function isShowInOverview(): ?bool
    {
        return $this->showInOverview;
    }

    public function setShowInOverview(bool $showInOverview): void
    {
        $this->showInOverview = $showInOverview;
    }

    public function getFieldValue(): mixed
    {
        return $this->fieldValue;
    }

    public function setFieldValue(mixed $fieldValue): void
    {
        $this->fieldValue = $fieldValue;
    }
}
