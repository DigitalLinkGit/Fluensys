<?php

namespace App\Entity;

use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Entity\Capture\Field\SystemComponentCollectionField;
use App\Repository\SystemComponentCaptureElementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SystemComponentCaptureElementRepository::class)]
class SystemComponentCaptureElement extends CaptureElement
{

    private SystemComponentCollectionField $componentsField;

    public function getComponentsField(): SystemComponentCollectionField
    {
        // Si le field existe déjà (hydration Doctrine ou form), on le réutilise
        foreach ($this->getFields() as $field) {
            if ($field instanceof SystemComponentCollectionField) {
                return $field;
            }
        }

        // Sinon, on le crée une seule fois
        $field = new SystemComponentCollectionField();
        $this->addField($field);

        return $field;
    }

}
