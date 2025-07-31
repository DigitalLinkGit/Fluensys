<?php

namespace App\Entity;

use App\Repository\FlexCaptureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlexCaptureRepository::class)]
class FlexCapture extends CaptureElement
{


}
