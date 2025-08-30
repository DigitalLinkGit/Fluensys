<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectTemplateForm;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project_template')]
final class ProjectTemplateController extends AbstractController
{
    #[Route(name: 'app_project_template_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        $all = $projectRepository->findAll();
        $templates = array_filter($all, fn($el) => $el->isTemplate());

        return $this->render('project_template/index.html.twig', [
            'projects' => $templates,
        ]);
    }

    #[Route('/new', name: 'app_project_template_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectTemplateForm::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project_template/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_project_template_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('project_template/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_template_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectTemplateForm::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_project_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project_template/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_project_template_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_project_template_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/select',name: 'app_project_template_select', methods: ['GET'])]
    public function select(Request $request, EntityManagerInterface $em): Response
    {
        $all   = $em->getRepository(Project::class)->findAll();
        $templates = array_filter($all, fn($el) => $el->isTemplate());

        return $this->render('capture/select.html.twig', [
            'projects' => $templates,
        ]);
    }

    #[Route('{id}/clone',name: 'app_project_template_clone', methods: ['GET'])]
    public function clone(Request $request, EntityManagerInterface $em): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectTemplateForm::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('app_project_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project_template/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }
}
