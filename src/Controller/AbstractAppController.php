<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractAppController extends AbstractController
{
    public function __construct(protected LoggerInterface $logger)
    {
    }
}
