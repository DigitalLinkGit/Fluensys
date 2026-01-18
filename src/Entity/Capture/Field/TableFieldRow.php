<?php

namespace App\Entity\Capture\Field;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TableFieldRow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TableField $tableField = null;

    #[ORM\Column]
    private int $position = 0;

    #[ORM\Column(name: 'row_values', type: 'json', nullable: true)]
    private array $values = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTableField(): ?TableField
    {
        return $this->tableField;
    }

    public function setTableField(?TableField $tableField): static
    {
        $this->tableField = $tableField;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function setValues(array $values): static
    {
        $this->values = $values;

        return $this;
    }

    public function getValueFor(string $key): mixed
    {
        return $this->values[$key] ?? null;
    }

    public function setValueFor(string $key, mixed $value): static
    {
        $this->values[$key] = $value;

        return $this;
    }
}
