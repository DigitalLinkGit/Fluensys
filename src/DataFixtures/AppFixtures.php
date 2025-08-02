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
        $r1 = new ParticipantRole();
        $r1->setInternal(false);
        $r1->setName("répondant");
        $r1->setDescription("Externe");
        $manager->persist($r1);

        $r2 = new ParticipantRole();
        $r2->setInternal(true);
        $r2->setName("responsable");
        $r2->setDescription("Interne");
        $manager->persist($r2);

        $r3 = new ParticipantRole();
        $r3->setInternal(true);
        $r3->setName("valideur");
        $r3->setDescription("Interne");
        $manager->persist($r3);

        $f1 = new TextAreaField();
        $f1->setPosition(1);
        $f1->setExternalLabel("Textarea");
        $f1->setRequired(true);
        $f1->setTechnicalName("Textarea");
        $manager->persist($f1);

        $f2 = new IntegerField();
        $f2->setPosition(1);
        $f2->setExternalLabel("Integer");
        $f2->setRequired(false);
        $f2->setTechnicalName("Integer");
        $manager->persist($f2);

        $flex = new flexCapture();
        $flex->setDescription("Flex description");
        $flex->setName("Flex");
        $flex->setRespondent($r1);
        $flex->setResponsible($r2);
        $flex->setValidator($r3);
        $flex->addField($f1);
        $flex->addField($f2);
        $manager->persist($flex);

        $manager->flush();
    }
}
