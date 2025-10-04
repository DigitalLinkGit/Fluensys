<?php

namespace App\Entity\Capture\CaptureElement;

use App\Repository\FlexCaptureElementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlexCaptureElementRepository::class)]
class FlexCaptureElement extends CaptureElement
{


}
