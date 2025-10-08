<?php

namespace App\Entity\Capture;

use App\Entity\Account\Account;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\CaptureRepository::class)]
#[ORM\Table(name: 'capture')]
class Capture extends CaptureTemplate
{
    #[ORM\ManyToOne(inversedBy: 'captures')]
    private ?Account $account = null;

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }
}
