<?php

namespace App\DataFixtures;

use App\Entity\Field\DateField;
use App\Entity\Field\DecimalField;
use App\Entity\Field\Field;
use App\Entity\Field\IntegerField;
use App\Entity\Field\TextAreaField;
use App\Entity\Field\TextField;
use App\Entity\FlexCapture;
use App\Entity\ParticipantRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $r1 = $this->createParticipantRole(
            false,
            'répondant',
            'Externe',
        );
        $manager->persist($r1);
        $r2 = $this->createParticipantRole(
            true,
            'responsable',
            'Interne',
        );
        $manager->persist($r2);
        $r3 = $this->createParticipantRole(
            true,
            'valideur',
            'Interne',
        );
        $manager->persist($r3);

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

        $manager->flush();
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
