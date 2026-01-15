<?php

namespace App\Entity;

use App\Entity\Interface\TenantAwareInterface;
use App\Entity\Trait\TenantAwareTrait;
use App\Repository\RenderingConfigRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RenderingConfigRepository::class)]
class RenderingConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $documentTitleColor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titleH1Color = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titleH2Color = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titleH3Color = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tableHeaderBackgroundColor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tableHeaderColor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $borderColor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoPath = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDocumentTitleColor(): ?string
    {
        return $this->documentTitleColor;
    }

    public function setDocumentTitleColor(?string $documentTitleColor): static
    {
        $this->documentTitleColor = $documentTitleColor;

        return $this;
    }

    public function getTitleH1Color(): ?string
    {
        return $this->titleH1Color;
    }

    public function setTitleH1Color(?string $titleH1Color): static
    {
        $this->titleH1Color = $titleH1Color;

        return $this;
    }

    public function getTitleH2Color(): ?string
    {
        return $this->titleH2Color;
    }

    public function setTitleH2Color(?string $titleH2Color): static
    {
        $this->titleH2Color = $titleH2Color;

        return $this;
    }

    public function getTitleH3Color(): ?string
    {
        return $this->titleH3Color;
    }

    public function setTitleH3Color(?string $titleH3Color): static
    {
        $this->titleH3Color = $titleH3Color;

        return $this;
    }

    public function getLogoPath(): ?string
    {
        return $this->logoPath;
    }

    public function setLogoPath(?string $logoPath): static
    {
        $this->logoPath = $logoPath;

        return $this;
    }

    public function getTableHeaderBackgroundColor(): ?string
    {
        return $this->tableHeaderBackgroundColor;
    }

    public function setTableHeaderBackgroundColor(?string $tableHeaderBackgroundColor): static
    {
        $this->tableHeaderBackgroundColor = $tableHeaderBackgroundColor;

        return $this;
    }

    public function getTableHeaderColor(): ?string
    {
        return $this->tableHeaderColor;
    }

    public function setTableHeaderColor(?string $tableHeaderColor): static
    {
        $this->tableHeaderColor = $tableHeaderColor;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->borderColor;
    }

    public function setBorderColor(?string $borderColor): static
    {
        $this->borderColor = $borderColor;

        return $this;
    }
}
