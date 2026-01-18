<?php

namespace App\Entity\Capture\Field;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TableField extends Field
{
    #[ORM\OneToMany(targetEntity: TableFieldColumn::class, mappedBy: 'tableField', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC', 'id' => 'ASC'])]
    private Collection $columns;

    #[ORM\OneToMany(targetEntity: TableFieldRow::class, mappedBy: 'tableField', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC', 'id' => 'ASC'])]
    private Collection $rows;

    public function __construct()
    {
        $this->columns = new ArrayCollection();
        $this->rows = new ArrayCollection();
    }

    public function __clone()
    {
        parent::__clone();

        // Clone columns
        $newColumns = new ArrayCollection();
        foreach ($this->getColumns() as $col) {
            $cloned = clone $col;
            $cloned->setTableField($this);
            $newColumns->add($cloned);
        }
        $this->columns = $newColumns;
        $this->rows = new ArrayCollection();
    }


    /** @return Collection<int, TableFieldColumn> */
    public function getColumns(): Collection
    {
        return $this->columns;
    }

    public function addColumn(TableFieldColumn $column): static
    {
        if (!$this->columns->contains($column)) {
            $this->columns->add($column);
            $column->setTableField($this);
        }

        return $this;
    }

    public function removeColumn(TableFieldColumn $column): static
    {
        if ($this->columns->removeElement($column)) {
            if ($column->getTableField() === $this) {
                $column->setTableField(null);
            }
        }

        return $this;
    }

    /** @return Collection<int, TableFieldRow> */
    public function getRows(): Collection
    {
        return $this->rows;
    }

    public function addRow(TableFieldRow $row): static
    {
        if (!$this->rows->contains($row)) {
            $this->rows->add($row);
            $row->setTableField($this);
        }

        return $this;
    }

    public function removeRow(TableFieldRow $row): static
    {
        if ($this->rows->removeElement($row)) {
            if ($row->getTableField() === $this) {
                $row->setTableField(null);
            }
        }

        return $this;
    }

    public function getValue(): mixed
    {
        $keys = array_values(array_map(
            static fn (TableFieldColumn $c) => (string) $c->getKey(),
            $this->columns->toArray()
        ));

        $out = [];
        foreach ($this->rows as $row) {
            $line = [];
            foreach ($keys as $k) {
                $line[$k] = $row->getValueFor($k);
            }
            $out[] = $line;
        }

        return $out;
    }

    public function syncColumnsFromRaw(?string $raw): void
    {
        $raw = trim((string) $raw);
        if ('' === $raw) {
            return;
        }

        $allowed = ['text', 'integer', 'date'];

        $existingByKey = [];
        foreach ($this->columns as $col) {
            $existingByKey[(string) $col->getKey()] = $col;
        }

        $seenKeys = [];
        $position = 0;

        foreach (preg_split("/\r\n|\n|\r/", $raw) as $line) {
            $line = trim((string) $line);
            if ('' === $line) {
                continue;
            }

            $parts = array_map('trim', explode('|', $line));
            $label = $parts[0] ?? '';
            if ('' === $label) {
                continue;
            }

            $type = strtolower($parts[1] ?? 'text');
            if (!in_array($type, $allowed, true)) {
                $type = 'text';
            }

            $baseKey = $this->normalizeKey($parts[2] ?? $label);
            if ('' === $baseKey) {
                $baseKey = 'col';
            }

            $key = $baseKey;
            $i = 2;
            while (in_array($key, $seenKeys, true)) {
                $key = $baseKey.'_'.$i;
                ++$i;
            }
            $seenKeys[] = $key;

            $col = $existingByKey[$key] ?? new TableFieldColumn();
            $col->setKey($key);
            $col->setLabel($label);
            $col->setType($type);
            $col->setPosition($position);

            if (!isset($existingByKey[$key])) {
                $this->addColumn($col);
            }

            ++$position;
        }

        foreach ($this->columns as $col) {
            if (!in_array((string) $col->getKey(), $seenKeys, true)) {
                $this->removeColumn($col);
            }
        }
    }

    private function normalizeKey(string $input): string
    {
        $key = transliterator_transliterate(
            'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
            (string) $input
        );

        $key = preg_replace('/\s+/', '_', $key);
        $key = preg_replace('/_+/', '_', (string) $key);
        $key = trim((string) $key, '_');

        return (string) $key;
    }
}
