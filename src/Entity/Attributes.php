<?php

namespace App\Entity;

use App\Repository\AttributesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttributesRepository::class)]
class Attributes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $attributeGroupCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attributeGroupLabel = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getAttributeGroupCode(): ?string
    {
        return $this->attributeGroupCode;
    }

    public function setAttributeGroupCode(string $attributeGroupCode): static
    {
        $this->attributeGroupCode = $attributeGroupCode;

        return $this;
    }

    public function getAttributeGroupLabel(): ?string
    {
        return $this->attributeGroupLabel;
    }

    public function setAttributeGroupLabel(?string $attributeGroupLabel): static
    {
        $this->attributeGroupLabel = $attributeGroupLabel;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
