<?php

namespace App\Entity\Capture\CaptureElement;

use App\Entity\Capture\Field\SystemComponentCollectionField;
use App\Repository\SystemComponentCaptureElementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SystemComponentCaptureElementRepository::class)]
class SystemComponentCaptureElement extends CaptureElement
{
    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    private function init()
    {
        $field = (new SystemComponentCollectionField())
            ->setName('components')
            ->setInternalPosition(1)
            ->setInternalRequired(true)
            ->setExternalRequired(true)
            ->setExternalLabel('Composants su SI')
            ->setInternalLabel('Composants su SI');
        $this->addField($field);
    }

}
