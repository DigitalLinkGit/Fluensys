<?php

namespace App\Controller;

use App\Entity\Capture;
use App\Entity\CaptureElement;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/capture-element')]
final class CaptureElementController extends AbstractController
{

    #[Route(name: 'app_capture_element_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $captureId = $request->query->getInt('capture');
        $capture   = $em->getRepository(Capture::class)->find($captureId);

        $all = $em->getRepository(CaptureElement::class)->findAll();
        $already = $capture ? $capture->getCaptureElements() : new ArrayCollection();

        $alreadyIds = array_map(fn($e) => $e->getId(), $already->toArray());
        $available = array_filter($all, fn($el) => !in_array($el->getId(), $alreadyIds, true));

        return $this->render('capture_element/index.html.twig', [
            'capture_elements' => $available,
            'capture_id' => $captureId,
        ]);
    }


    #[Route('/{id}', name: 'app_capture_element_show', methods: ['GET'])]
    public function show(CaptureElement $captureElement): Response
    {
        return $this->render('capture_element/show.html.twig', [
            'capture_element' => $captureElement,
        ]);
    }
}
