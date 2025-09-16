<?php

namespace App\Tests\Functional;

use App\Entity\Field\ChecklistField;
use App\Entity\FlexCaptureElement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChecklistFieldConfigTest extends WebTestCase
{
    private function createFlexCaptureWithChecklist(EntityManagerInterface $em): FlexCaptureElement
    {
        $fc = new FlexCaptureElement();
        $fc->setName('Test FC');
        $fc->setTemplate(true);

        $field = new ChecklistField();
        $field->setExternalLabel('Ext');
        $field->setInternalLabel('Int');
        $field->setRequired(false);
        $field->setPosition(0);
        $field->setCaptureElement($fc);
        $field->setChoices([
            ['label' => 'Option A', 'value' => 'Option A'],
            ['label' => 'Option B', 'value' => 'Option B'],
        ]);

        // Required participant roles
        $resp = (new \App\Entity\Participant\ParticipantRole())->setName('Resp')->setDescription('')->setInternal(true);
        $respnd = (new \App\Entity\Participant\ParticipantRole())->setName('Respnd')->setDescription('')->setInternal(false);
        $valid = (new \App\Entity\Participant\ParticipantRole())->setName('Valid')->setDescription('')->setInternal(true);
        $em->persist($resp);
        $em->persist($respnd);
        $em->persist($valid);
        $fc->setResponsible($resp);
        $fc->setRespondent($respnd);
        $fc->setValidator($valid);

        // FlexCaptureElement::addField() if exists, else use collection
        $fc->getFields()->add($field);

        $em->persist($fc);
        $em->persist($field);
        $em->flush();

        return $fc;
    }

    public function test_edit_page_shows_checklist_subtype_textarea(): void
    {
        $client = static::createClient();
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get(EntityManagerInterface::class);

        $fc = $this->createFlexCaptureWithChecklist($em);

        $client->request('GET', '/flex-capture/'.$fc->getId().'/edit');
        $this->assertResponseIsSuccessful();

        $html = $client->getResponse()->getContent();
        // The subtype textarea should be present
        $this->assertStringContainsString('Choix (1 par ligne', $html, 'Choices textarea label should be rendered');
        $this->assertStringContainsString('Option A', $html, 'Existing choices should prefill');
    }
}
