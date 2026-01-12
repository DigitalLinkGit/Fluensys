<?php

namespace App\Form\Participant;

use App\Entity\Account\Contact;
use App\Entity\Capture\Capture;
use App\Entity\Participant\ParticipantAssignment;
use App\Entity\Participant\ParticipantRole;
use App\Entity\Project;
use App\Entity\Tenant\User;
use App\Enum\ParticipantAssignmentPurpose;
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('responsible', EntityType::class, [
            'class' => User::class,
            'choice_label' => fn (User $u) => $u->getUsername(),
            'placeholder' => 'Sélectionner un responsable',
            'required' => true,
            'label' => false,
            'attr' => [
                'class' => 'w-auto',
            ],
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $owner = $event->getData();
            $form = $event->getForm();

            if (!$owner instanceof Capture && !$owner instanceof Project) {
                return;
            }

            foreach ($owner->getContributorRoles() as $role) {
                \assert($role instanceof ParticipantRole);
                $this->addRoleSelectField(
                    $form,
                    $owner,
                    $role,
                    'contrib_role_'.$role->getId(),
                    ParticipantAssignmentPurpose::CONTRIBUTOR
                );
            }

            foreach ($owner->getValidatorRoles() as $role) {
                \assert($role instanceof ParticipantRole);
                $this->addRoleSelectField(
                    $form,
                    $owner,
                    $role,
                    'valid_role_'.$role->getId(),
                    ParticipantAssignmentPurpose::VALIDATOR
                );
            }
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event): void {
            $owner = $event->getData();
            $form = $event->getForm();

            if (!$owner instanceof Capture && !$owner instanceof Project) {
                return;
            }

            $this->syncAssignments(
                $owner,
                $form,
                $owner->getContributorRoles(),
                'contrib_role_',
                ParticipantAssignmentPurpose::CONTRIBUTOR
            );

            $this->syncAssignments(
                $owner,
                $form,
                $owner->getValidatorRoles(),
                'valid_role_',
                ParticipantAssignmentPurpose::VALIDATOR
            );
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }



    private function addRoleSelectField(
        FormInterface $form,
        Capture|Project $owner,
        ParticipantRole $role,
        string $fieldName,
        ParticipantAssignmentPurpose $purpose,
    ): void {
        $assignment = $this->findAssignment($owner, $role, $purpose);

        if ((bool) $role->isInternal()) {
            $form->add($fieldName, EntityType::class, [
                'class' => User::class,
                'choice_label' => fn (User $u) => $u->getUsername(),
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

        $account = method_exists($owner, 'getAccount') ? $owner->getAccount() : null;

        $form->add($fieldName, EntityType::class, [
            'class' => Contact::class,
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

    private function syncAssignments(
        Capture|Project $owner,
        FormInterface $form,
        array $roles,
        string $prefix,
        ParticipantAssignmentPurpose $purpose,
    ): void {
        foreach ($roles as $role) {
            \assert($role instanceof ParticipantRole);

            $fieldName = $prefix.(string) $role->getId();

            if (!$form->has($fieldName)) {
                continue;
            }

            $selected = $form->get($fieldName)->getData();
            $assignment = $this->findAssignment($owner, $role, $purpose);

            if (null === $selected) {
                if (null !== $assignment) {
                    $owner->removeParticipantAssignment($assignment);
                }
                continue;
            }

            if (null === $assignment) {
                $assignment = new ParticipantAssignment();
                $assignment->setRole($role);
                $assignment->setPurpose($purpose);

                if ($owner instanceof Capture) {
                    $assignment->setCapture($owner);
                } else {
                    $assignment->setProject($owner);
                }

                $owner->addParticipantAssignment($assignment);
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

    private function findAssignment(
        Capture|Project $owner,
        ParticipantRole $role,
        ParticipantAssignmentPurpose $purpose,
    ): ?ParticipantAssignment {
        foreach ($owner->getParticipantAssignments() as $assignment) {
            if (
                $assignment->getRole()?->getId() === $role->getId()
                && $assignment->getPurpose() === $purpose
            ) {
                return $assignment;
            }
        }

        return null;
    }
}
