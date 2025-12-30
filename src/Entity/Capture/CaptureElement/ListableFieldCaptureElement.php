<?php

namespace App\Entity\Capture\CaptureElement;

use App\Entity\Capture\Field\ListableField;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
class ListableFieldCaptureElement extends CaptureElement
{

    public function __construct()
    {
        parent::__construct();
        $field = new ListableField();
        $field->setLabel('Listez ...');
        $field->setName('Liste');
        $field->setPosition(0);
        $field->setRequired(true);
        $this->addField($field);
    }
}
