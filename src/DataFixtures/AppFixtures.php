<?php

namespace App\DataFixtures;

use App\Entity\FlexCapture;
use App\Entity\ParticipantRole;
use App\Entity\TextAreaField;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $r1 = new ParticipantRole();
        $r1->setInternal(false);
        $r1->setName("externe");
        $r1->setDescription("Externe");
        $manager->persist($r1);

        $r2 = new ParticipantRole();
        $r2->setInternal(true);
        $r2->setName("interne");
        $r2->setDescription("Interne");
        $manager->persist($r2);

        $f1 = new TextAreaField();
        $f1->setPosition(1);
        $f1->setLabel("Label");
        $f1->setRequired(false);
        $f1->setTechnicalName("TechnicalName");
        $manager->persist($f1);

        $flex = new flexCapture();
        $flex->setDescription("Flex description");
        $flex->setName("Flex");
        $flex->addParticipantRole($r1);
        $flex->addParticipantRole($r2);
        $flex->addField($f1);
        $manager->persist($flex);

        $manager->flush();
    }
}
