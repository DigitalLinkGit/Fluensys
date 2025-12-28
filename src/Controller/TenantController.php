<?php

namespace App\Controller;

use App\Entity\Participant\User;
use App\Entity\Tenant;
use App\Form\Participant\UserForm;
use App\Form\TenantForm;
use App\Repository\TenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tenant')]
final class TenantController extends AbstractController
{
    #[Route(name: 'app_tenant_index', methods: ['GET'])]
    public function index(TenantRepository $tenantRepository): Response
    {
        return $this->render('tenant/index.html.twig', [
            'tenants' => $tenantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tenant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tenant = new Tenant();
        $form = $this->createForm(TenantForm::class, $tenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tenant);
            $entityManager->flush();

            return $this->redirectToRoute('app_tenant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tenant/new.html.twig', [
            'tenant' => $tenant,
            'users' => $tenant->getUsers(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tenant_show', methods: ['GET'])]
    public function show(Tenant $tenant): Response
    {
        return $this->render('tenant/show.html.twig', [
            'tenant' => $tenant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tenant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tenant $tenant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TenantForm::class, $tenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tenant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tenant/edit.html.twig', [
            'tenant' => $tenant,
            'users' => $tenant->getUsers(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tenant_delete', methods: ['POST'])]
    public function delete(Request $request, Tenant $tenant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tenant->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tenant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tenant_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/tenant/{id}/user/new', name: 'app_tenant_user_new', methods: ['GET', 'POST'])]
    public function newForTenant(
        Tenant $tenant,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
    ): Response {
        $user = new User();
        $user->setTenant($tenant);

        $form = $this->createForm(UserForm::class, $user, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // force à nouveau, au cas où (sécurité)
            $user->setTenant($tenant);

            $plainPassword = (string) $form->get('plainPassword')->getData();
            if ('' !== $plainPassword) {
                $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_administration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/new.html.twig', [
            'tenant' => $tenant,
            'user' => $user,
            'form' => $form,
        ]);
    }
}
