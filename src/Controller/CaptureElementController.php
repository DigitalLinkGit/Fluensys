<?php

namespace App\Controller;

use App\Repository\CaptureElementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/capture-element')]
final class CaptureElementController extends AbstractController
{
    #[Route(name: 'app_capture_element_index', methods: ['GET'])]
    public function index(CaptureElementRepository $captureElementRepository): Response
    {
        return $this->render('capture_element/index.html.twig', [
            'capture_elements' => $captureElementRepository->findAll(),
        ]);
    }
}
