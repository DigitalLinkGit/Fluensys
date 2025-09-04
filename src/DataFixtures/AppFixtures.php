<?php

namespace App\DataFixtures;

use App\Entity\Capture;
use App\Entity\Field\ChecklistField;
use App\Entity\Field\DateField;
use App\Entity\Field\DecimalField;
use App\Entity\Field\Field;
use App\Entity\Field\IntegerField;
use App\Entity\Field\TextAreaField;
use App\Entity\Field\TextField;
use App\Entity\FlexCaptureElement;
use App\Entity\InformationSystem;
use App\Entity\ParticipantRole;
use App\Entity\Rendering\TextChapter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Roles
        $r1 = $this->createParticipantRole(
            false,
            'Responsable métier',
            'Responsable du métier côté client',
        );

        $manager->persist($r1);
        $r2 = $this->createParticipantRole(
            true,
            'Consultant',
            'Consultant',
        );
        $manager->persist($r2);
        $r3 = $this->createParticipantRole(
            true,
            'Consultant expert',
            'Consultant expert',
        );
        $manager->persist($r3);

        $r4 = $this->createParticipantRole(
            true,
            'Chef de projet',
            'Chef de projet',
        );
        $manager->persist($r4);

        //Fields
        $f1 = $this->createField(
            new TextAreaField(),
            1,
            "Textarea",
            "Textarea",
            true,
            "TEXTAREA"
        );
        $manager->persist($f1);

        $f2 = $this->createField(
            new IntegerField(),
            2,
            "Integer",
            "Integer",
            false,
            "INTEGER"
        );
        $manager->persist($f2);

        $f3 = $this->createField(
            new TextField(),
            3,
            "Text",
            "Text",
            false,
            "TEXT"
        );
        $manager->persist($f3);

        $f4 = $this->createField(
            new DecimalField(),
            4,
            "Decimal",
            "Decimal",
            true,
            "DECIMAL"
        );
        $manager->persist($f4);

        $f5 = $this->createField(
            new DateField(),
            5,
            "Date",
            "Date",
            true,
            "DATE"
        );
        $manager->persist($f5);

        $f6 = $this->createField(
            new ChecklistField(),
            5,
            "Checklist",
            "Checklist",
            true,
            "CHECKLIST",
            [
                ['label' => 'Option A', 'value' => 'Option A'],
                ['label' => 'Option B', 'value' => 'Option B'],
                ['label' => 'Option C', 'value' => 'Option C'],
            ]
        );
        $manager->persist($f6);

        $flex = (new FlexCaptureElement())
            ->setDescription("Flex capture utilisée pour vérifier que tous les types de fields fonctionnent et s'affichent correctement")
            ->setName("Flex test fields")
            ->setRespondent($r1)
            ->setResponsible($r2)
            ->setValidator($r3)
            ->addField($f1)
            ->addField($f2)
            ->addField($f3)
            ->addField($f4)
            ->addField($f5)
            ->addField($f6);
        $manager->persist($flex);

        $chapter = (new TextChapter())
            ->setTitle("Test du rendu des types de champs")
            ->setLevel(2)
            ->setTemplateContent(
                "Textarea : [TEXTAREA]
                Integer : [INTEGER]
                Text : [TEXT]
                Decimal : [DECIMAL]
                Date : [DATE]
                Checklist : [CHECKLIST]");
        $chapter->setCaptureElement($flex);
        $manager->persist($chapter);

        //Fields
        $f10 = $this->createField(
            new TextAreaField(),
            2,
            "Décrivez votre activité en quelques lignes",
            "Activité",
            false,
            "ACTIVITY"
        );
        $manager->persist($f10);

        $f20 = $this->createField(
            new IntegerField(),
            3,
            "Combien de salariés travail dans votre entreprise ?",
            "Nombre de salarié",
            true,
            "NB_EMPLOYEE"
        );
        $manager->persist($f20);

        $f30 = $this->createField(
            new TextField(),
            1,
            "Quel est le nom de votre société ?",
            "Nom",
            true,
            "NAME"
        );
        $manager->persist($f30);

        $f50 = $this->createField(
            new DateField(),
            4,
            "Quand avez vous commencez votre activité ?",
            "Date de début d'activité",
            false,
            "ACTIVITY_START_DATE"
        );
        $manager->persist($f50);

        $f60 = $this->createField(
            new ChecklistField(),
            5,
            "Cochez les options qui rentrent dans votre activité",
            "Scope",
            true,
            "SCOPE",
            [
                ['label' => 'Gestion de projet', 'value' => 'Gestion de projet'],
                ['label' => 'RH', 'value' => 'RH'],
                ['label' => 'Facturation', 'value' => 'Facturation'],
                ['label' => 'Prospection', 'value' => 'Prospection'],
                ['label' => 'Logistique', 'value' => 'Logistique'],
                ['label' => 'Production', 'value' => 'Production'],
                ['label' => 'Achat', 'value' => 'Achat'],
            ]
        );
        $manager->persist($f60);


        $flex2 = (new FlexCaptureElement())
            ->setDescription("Recueil des informations classiques sur le compte")
            ->setName("Informations générale")
            ->setRespondent($r1)
            ->setResponsible($r2)
            ->setValidator($r3)
            ->addField($f10)
            ->addField($f20)
            ->addField($f30)
            ->addField($f50)
            ->addField($f60);
        $manager->persist($flex2);

        $chapter2 = (new TextChapter())
            ->setTitle("Activité")
            ->setLevel(2)
            ->setTemplateContent("[NAME] est une société de [NB_EMPLOYEE] qui a démarée son activité le [ACTIVITY_START_DATE].
            [ACTIVITY]

            Scope :
            [SCOPE]
            ");
        $chapter2->setCaptureElement($flex2);
        $manager->persist($chapter2);

        $capture = (new Capture())
            ->setName("Information du compte")
            ->setDescription("Recueil des informations sur le compte")
            ->addCaptureElement($flex)
            ->addCaptureElement($flex2)
            ->setTemplate(true);
        $manager->persist($capture);

        $is1 = $this->createInformationSystem("Compte avec SI 1");
        $manager->persist($is1);
        $is2 = $this->createInformationSystem("Compte avec SI 2");
        $manager->persist($is2);

        $manager->flush();
    }

    private function createInformationSystem(string $name): InformationSystem
    {
        return (new InformationSystem())
            ->setName($name);
    }
    private function createParticipantRole(bool $internal, string $name, string $description): ParticipantRole
    {
        return (new ParticipantRole())
            ->setInternal($internal)
            ->setName($name)
            ->setDescription($description);
    }

    private function createField(Field $field, int $position, string $externalLabel, string $internalLabel, bool $required, string $technicalName, ?array $choices = null): Field
    {
        $field
            ->setPosition($position)
            ->setExternalLabel($externalLabel)
            ->setInternalLabel($internalLabel)
            ->setRequired($required)
            ->setTechnicalName($technicalName);

        if ($choices !== null && $field instanceof ChecklistField) {
            $field->setChoices($choices);
        }

        return $field;
    }

}
