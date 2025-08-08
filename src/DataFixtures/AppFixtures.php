<?php

namespace App\DataFixtures;

use App\Entity\Field\IntegerField;
use App\Entity\Field\TextAreaField;
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
        $r2 = $this->createParticipantRole(
            true,
            'responsable',
            'Interne',
        );
        $r3 = $this->createParticipantRole(
            true,
            'valideur',
            'Interne',
        );

        $manager->persist($r1);
        $manager->persist($r2);
        $manager->persist($r3);

        $f1 = (new TextAreaField())
            ->setPosition(1)
            ->setExternalLabel("Textarea")
            ->setInternalLabel("InternalLabel")
            ->setRequired(true)
            ->setTechnicalName("Textarea");
        $manager->persist($f1);

        $f2 = (new IntegerField())
            ->setPosition(1)
            ->setExternalLabel("Integer")
            ->setInternalLabel("InternalLabel")
            ->setRequired(false)
            ->setTechnicalName("Integer");
        $manager->persist($f2);

        $flex = (new flexCapture())
            ->setDescription("Flex description")
            ->setName("Flex")
            ->setRespondent($r1)
            ->setResponsible($r2)
            ->setValidator($r3)
            ->addField($f1)
            ->addField($f2);
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
}
