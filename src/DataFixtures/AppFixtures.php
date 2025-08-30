<?php

namespace App\DataFixtures;

use App\Entity\Capture;
use App\Entity\Field\DateField;
use App\Entity\Field\DecimalField;
use App\Entity\Field\Field;
use App\Entity\Field\IntegerField;
use App\Entity\Field\TextAreaField;
use App\Entity\Field\TextField;
use App\Entity\FlexCapture;
use App\Entity\InformationSystem;
use App\Entity\ParticipantRole;
use App\Entity\Project;
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
            "Internal Label",
            true,
            "TEXTAREA"
        );
        $manager->persist($f1);

        $f2 = $this->createField(
            new IntegerField(),
            2,
            "Integer",
            "Internal Label",
            false,
            "INTEGER"
        );
        $manager->persist($f2);

        $f3 = $this->createField(
            new TextField(),
            3,
            "Text",
            "Internal Label",
            false,
            "TEXT"
        );
        $manager->persist($f3);

        $f4 = $this->createField(
            new DecimalField(),
            4,
            "Decimal",
            "Internal Label",
            true,
            "DECIMAL"
        );
        $manager->persist($f4);

        $f5 = $this->createField(
            new DateField(),
            5,
            "Date",
            "Internal Label",
            true,
            "DATE"
        );
        $manager->persist($f5);

        $flex = (new flexCapture())
            ->setDescription("Flex description")
            ->setName("Flex")
            ->setRespondent($r1)
            ->setResponsible($r2)
            ->setValidator($r3)
            ->addField($f1)
            ->addField($f2)
            ->addField($f3)
            ->addField($f4)
            ->addField($f5);
        $manager->persist($flex);

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

        $flex2 = (new flexCapture())
            ->setDescription("Recueil des informations classiques sur le compte")
            ->setName("Informations générale")
            ->setRespondent($r1)
            ->setResponsible($r2)
            ->setValidator($r3)
            ->addField($f10)
            ->addField($f20)
            ->addField($f30)
            ->addField($f50);
        $manager->persist($flex2);

        $capture = (new Capture())
            ->setName("Information du compte")
            ->setDescription("Recueil des informations sur le compte")
            ->addCaptureElement($flex)
            ->addCaptureElement($flex2)
            ->setTemplate(true);
        $manager->persist($capture);

        $is = $this->createInformationSystem("Système d'information");
        $manager->persist($is);

        $pro = $this->createProject(
            "Premier projet",
            "Projet avec capture des informations de base sur le compte",
            "draft",
            true,
            $is,
            $capture
        );

        $manager->persist($pro);


        $manager->flush();
    }

    private function createInformationSystem(string $name): InformationSystem
    {
        return (new InformationSystem())
            ->setName($name);
    }
    private function createProject(string $name, string $description, string $status, bool $isTemplate, InformationSystem $is, Capture $capture): Project
    {
        return (new Project())
            ->setName($name)
            ->setDescription($description)
            ->setStatus($status)
            ->setTemplate($isTemplate)
            ->setInformationSystem($is)
            ->addCapture($capture);
    }
    private function createParticipantRole(bool $internal, string $name, string $description): ParticipantRole
    {
        return (new ParticipantRole())
            ->setInternal($internal)
            ->setName($name)
            ->setDescription($description);
    }

    private function createField(Field $field, int $position, string $externalLabel, string $internalLabel, bool $required, string $technicalName): Field
    {
        return $field
            ->setPosition($position)
            ->setExternalLabel($externalLabel)
            ->setInternalLabel($internalLabel)
            ->setRequired($required)
            ->setTechnicalName($technicalName);
    }

}
