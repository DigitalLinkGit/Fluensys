<?php

namespace App\Form\Participant;

use App\Entity\Account\Contact;
use App\Entity\Capture\Capture;
use App\Entity\Participant\ParticipantAssignment;
use App\Entity\Participant\ParticipantRole;
use App\Entity\Tenant\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantAssignmentForm extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Capture $capture */
        $capture = $builder->getData();

        $builder->add('responsible', EntityType::class, [
            'class' => \App\Entity\Tenant\User::class,
            'choice_label' => fn (\App\Entity\Tenant\User $u) => $u->getUsername(),
            'placeholder' => 'Sélectionner un responsable',
            'required' => true,
            'label' => false,
            'attr' => [
                'class' => 'w-auto',
            ],
        ]);

        $contributorRoles = $capture->getContributorRoles();
        $validatorRoles = $capture->getValidatorRoles();

        // Build dynamic selects (mapped = false): one select per expected role.
        foreach ($contributorRoles as $role) {
            \assert($role instanceof ParticipantRole);
            $this->addRoleSelectField($builder, $capture, $role, 'contrib_role_'.(string) $role->getId());
        }

        foreach ($validatorRoles as $role) {
            \assert($role instanceof ParticipantRole);
            $this->addRoleSelectField($builder, $capture, $role, 'valid_role_'.(string) $role->getId());
        }

        // Sync ParticipantAssignment collection from the dynamic fields.
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($contributorRoles, $validatorRoles): void {
            /** @var Capture $capture */
            $capture = $event->getData();
            $form = $event->getForm();

            $this->syncAssignments($capture, $form, $contributorRoles, 'contrib_role_');
            $this->syncAssignments($capture, $form, $validatorRoles, 'valid_role_');
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Capture::class]);
    }

    private function addRoleSelectField(FormBuilderInterface $builder, Capture $capture, ParticipantRole $role, string $fieldName): void
    {

        $assignment = $this->findAssignment($capture, $role);

        if ((bool) $role->isInternal()) {
            $builder->add($fieldName, EntityType::class, [
                'class' => User::class,
                'choice_label' => fn (\App\Entity\Tenant\User $u) => $u->getUsername(),
                'placeholder' => 'Sélectionner un utilisateur',
                'required' => false,
                'mapped' => false,
                'data' => $assignment?->getUser(),
                'query_builder' => function (EntityRepository $er) use ($role) {
                    return $er->createQueryBuilder('u')
                        ->innerJoin('u.participantRoles', 'r')
                        ->andWhere('r = :role')
                        ->setParameter('role', $role)
                        ->orderBy('u.username', 'ASC');
                },
            ]);

            return;
        }

        $account = $capture->getAccount();

        $builder->add($fieldName, EntityType::class, [
            'class' => \App\Entity\Account\Contact::class,
            'choice_label' => fn (Contact $c) => $c->getName(),
            'placeholder' => 'Sélectionner un contact',
            'required' => false,
            'mapped' => false,
            'data' => $assignment?->getContact(),
            'query_builder' => function (EntityRepository $er) use ($role, $account) {
                $qb = $er->createQueryBuilder('c')
                    ->innerJoin('c.participantRoles', 'r')
                    ->andWhere('r = :role')
                    ->setParameter('role', $role)
                    ->orderBy('c.name', 'ASC');

                if (null === $account) {
                    return $qb->andWhere('1 = 0');
                }

                return $qb
                    ->andWhere('c.account = :account')
                    ->setParameter('account', $account);
            },
        ]);
    }

    private function syncAssignments(Capture $capture, FormInterface $form, array $roles, string $prefix): void
    {
        foreach ($roles as $role) {
            \assert($role instanceof ParticipantRole);

            $fieldName = $prefix.(string) $role->getId();

            if (!$form->has($fieldName)) {
                continue;
            }

            $selected = $form->get($fieldName)->getData();
            $assignment = $this->findAssignment($capture, $role);

            if (null === $selected) {
                if (null !== $assignment) {
                    $capture->removeParticipantAssignment($assignment);
                }
                continue;
            }

            if (null === $assignment) {
                $assignment = new ParticipantAssignment();
                $assignment->setCapture($capture);
                $assignment->setRole($role);
                $capture->addParticipantAssignment($assignment);
            }

            if ((bool) $role->isInternal()) {
                \assert($selected instanceof User);
                $assignment->setUser($selected);
                $assignment->setContact(null);
            } else {
                \assert($selected instanceof Contact);
                $assignment->setContact($selected);
                $assignment->setUser(null);
            }
        }
    }

    private function findAssignment(Capture $capture, ParticipantRole $role): ?ParticipantAssignment
    {
        foreach ($capture->getParticipantAssignments() as $assignment) {
            if ($assignment->getRole()?->getId() === $role->getId()) {
                return $assignment;
            }
        }

        return null;
    }
}
