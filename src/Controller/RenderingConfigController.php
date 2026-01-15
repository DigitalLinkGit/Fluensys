<?php

namespace App\Controller;

use App\Entity\RenderingConfig;
use App\Entity\Tenant\User;
use App\Form\RenderingConfigForm;
use App\Repository\RenderingConfigRepository;
use App\Service\Helper\FileUploadManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rendering/config')]
final class RenderingConfigController extends AbstractController
{
    #[Route('/edit', name: 'app_rendering_config_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, FileUploadManager $uploadManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $renderingConfig = $user->getTenant()->getRenderingConfig();
        if (null === $renderingConfig) {
            $renderingConfig = new RenderingConfig();
            $user->getTenant()->setRenderingConfig($renderingConfig);
        }
        $form = $this->createForm(RenderingConfigForm::class, $renderingConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $oldPath = $renderingConfig->getLogoPath();

            /** @var UploadedFile|null $logoFile */
            $logoFile = $form->get('logoFile')->getData();

            if ($logoFile) {
                $newPath = $uploadManager->upload($logoFile, 'logos', 'logo');
                // remove previous file
                $uploadManager->delete($oldPath);
                $renderingConfig->setLogoPath($newPath);
            }
            $entityManager->persist($renderingConfig);
            $entityManager->flush();

            return $this->redirectToRoute('app_tenant_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rendering_config/edit.html.twig', [
            'rendering_config' => $renderingConfig,
            'form' => $form,
        ]);
    }

}
