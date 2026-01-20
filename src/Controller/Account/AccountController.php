<?php

namespace App\Controller\Account;

use App\Entity\Account\Account;
use App\Entity\Account\Contact;
use App\Entity\Tenant\Tenant;
use App\Form\Account\AccountForm;
use App\Repository\AccountRepository;
use App\Service\Helper\ActivityLogProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account')]
final class AccountController extends AbstractController
{
    public function __construct(
        private readonly ActivityLogProvider $activityLogProvider,
    ) {
    }

    #[Route(name: 'app_account_index', methods: ['GET'])]
    public function index(AccountRepository $accountRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('account/index.html.twig', [
            'accounts' => $accountRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var \App\Entity\Tenant\User $user */
        $user = $this->getUser();

        $account = new Account();
        $form = $this->createForm(AccountForm::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->clear();
            $tenant = $entityManager->getRepository(Tenant::class)->find($user->getTenant()->getId());
            $account->setTenant($tenant);
            $entityManager->persist($account);
            $entityManager->flush();
            $this->addFlash('success', 'Le compte a été créé');

            return $this->redirectToRoute('app_account_edit', [
                'id' => $account->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/new.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_show', methods: ['GET'])]
    public function show(Account $account): Response
    {
        return $this->render('account/show.html.twig', [
            'account' => $account,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Account $account, EntityManagerInterface $entityManager): Response
    {
        $defaultContactId = $request->get('defaultContactId');

        $form = $this->createForm(AccountForm::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($defaultContactId) {
                    $account->setDefaultContact($entityManager->find(Contact::class, $defaultContactId));
                }
                $entityManager->persist($account);
                $entityManager->flush();
                $this->addFlash('success', 'Le compte a été enregistré');
            } else {
                foreach ($form->getErrors(true, true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }
        }
        $activity_logs = $this->activityLogProvider->forAccount($account);

        return $this->render('account/edit.html.twig', [
            'account' => $account,
            'form' => $form,
            'activity_logs' => $activity_logs,
        ]);
    }

    #[Route('/{id}', name: 'app_account_delete', methods: ['POST'])]
    public function delete(Request $request, Account $account, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$account->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($account);
            $entityManager->flush();
            $this->addFlash('success', 'Le compte a été supprimé');
        }

        return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
    }
}
