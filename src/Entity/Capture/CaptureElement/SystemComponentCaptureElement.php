<?php

namespace App\Entity\Capture\CaptureElement;

use App\Entity\Capture\Field\FieldConfig;
use App\Entity\Capture\Field\SystemComponentCollectionField;
use App\Entity\Capture\Rendering\Title;
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
        $intConfig = (new FieldConfig())
            ->setLabel('Composants su SI')
            ->setRequired(true);
        $extConfig = (new FieldConfig())
            ->setLabel('Listez / Vérifiez les composants de votre SI')
            ->setRequired(true);
        $title = (new Title())
            ->setContent('Composants de SI')
            ->setLevel(2);
        $field = (new SystemComponentCollectionField())
            ->setName('components')
            ->setPosition(1)
            ->setInternalConfig($intConfig)
            ->setExternalConfig($extConfig);
        $this->addField($field);
    }
}
