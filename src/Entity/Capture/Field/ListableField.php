<?php

namespace App\Entity\Capture\Field;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ListableField extends Field
{
    #[ORM\OneToMany(targetEntity: ListableFieldTextItem::class, mappedBy: 'listableField', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ListableFieldTextItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setListableField($this);
        }

        return $this;
    }

    public function removeItem(ListableFieldTextItem $item): static
    {
        if ($this->items->removeElement($item)) {
            if ($item->getListableField() === $this) {
                $item->setListableField(null);
            }
        }

        return $this;
    }

    public function getValue(): mixed
    {
        return array_values(array_map(
            static fn (ListableFieldTextItem $i) => (string) $i->getValue(),
            $this->items->toArray()
        ));
    }

    public function getStringValue(?string $dateFormat = null, ?string $listSeparator = null): string
    {
        $listSeparator ??= "\r\n";

        $values = array_values(array_filter(array_map(
            static fn (ListableFieldTextItem $i) => trim((string) $i->getValue()),
            $this->items->toArray()
        ), static fn (string $v) => '' !== $v));

        return implode($listSeparator, $values);
    }
}
