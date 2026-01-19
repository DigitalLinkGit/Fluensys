<?php

namespace App\Form\Capture\CaptureElement;

use App\Entity\Participant\ParticipantRole;
use App\Repository\ParticipantRoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureElementBaseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de l\'élémént...',
                ],
                'required' => true,
            ])
            ->add('contributor', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => function (?ParticipantRole $role): string {
                    if (!$role) {
                        return '';
                    }

                    $scope = $role->isInternal() ? 'Interne' : 'Externe';

                    return sprintf('%s - %s', $scope, $role->getName());
                },
                'label' => 'Contributeur',
                'required' => false,
                'query_builder' => function (ParticipantRoleRepository $r) {
                    return $r->createQueryBuilder('role')
                        ->orderBy('role.name', 'ASC');
                },
                'placeholder' => 'Aucune contribution',
                'attr' => [
                    'title' => 'Si un rôle est sélectionné, le responsable de la capture ne pourra répondre à cet élément que s’il possède ce rôle. Sinon, il devra solliciter un contributeur disposant du rôle requis.',
                    'class' => 'w-auto',
                ],
            ])
            ->add('validator', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => function (?ParticipantRole $role): string {
                    if (!$role) {
                        return '';
                    }

                    $scope = $role->isInternal() ? 'Interne' : 'Externe';

                    return sprintf('%s - %s', $scope, $role->getName());
                },
                'label' => 'Validateur',
                'required' => false,
                'query_builder' => function (ParticipantRoleRepository $r) {
                    return $r->createQueryBuilder('role')
                        ->andWhere('role.internal = :internal')
                        ->setParameter('internal', true)
                        ->orderBy('role.name', 'ASC');
                },
                'placeholder' => 'Aucune validation',
                'attr' => [
                    'title' => 'Si un rôle est sélectionné, le responsable de la capture ne pourra valider cet élément que s’il possède ce rôle. Sinon, il devra solliciter un contributeur disposant du rôle requis.',
                    'class' => 'w-auto',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Description de la capture...',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
