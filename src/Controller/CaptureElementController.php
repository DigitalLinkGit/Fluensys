<?php

namespace App\Controller;

use App\Entity\CaptureElement;
use App\Form\CaptureElementForm;
use App\Repository\CaptureElementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
