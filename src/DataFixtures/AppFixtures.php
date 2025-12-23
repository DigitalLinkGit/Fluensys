<?php

namespace App\DataFixtures;

use App\Entity\Account\Account;
use App\Entity\Account\Contact;
use App\Entity\Account\InformationSystem;
use App\Entity\Account\SystemComponent;
use App\Entity\Capture\Capture;
use App\Entity\Capture\CaptureElement\FlexCaptureElement;
use App\Entity\Capture\Condition;
use App\Entity\Capture\Field\ChecklistField;
use App\Entity\Capture\Field\DateField;
use App\Entity\Capture\Field\DecimalField;
use App\Entity\Capture\Field\EmailField;
use App\Entity\Capture\Field\Field;
use App\Entity\Capture\Field\IntegerField;
use App\Entity\Capture\Field\SystemComponentCollectionField;
use App\Entity\Capture\Field\TextAreaField;
use App\Entity\Capture\Field\TextField;
use App\Entity\Capture\Field\UrlField;
use App\Entity\Capture\Rendering\TextChapter;
use App\Entity\Capture\Rendering\Title;
use App\Entity\Participant\ParticipantRole;
use App\Entity\Participant\User;
use App\Enum\SystemComponentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        /* ===================================== Roles ===================================== */
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

        /* ===================================== Users ===================================== */
        // Admin
        $admin = $this->createUser(
            $this->passwordHasher,
            'admin@example.com',
            'admin',
            'Admin123!',
            ['ROLE_ADMIN']
        );
        $manager->persist($admin);

        // Standard user
        $user = $this->createUser(
            $this->passwordHasher,
            'user@example.com',
            'user standard',
            'User123!',
            ['ROLE_USER'],
            [$r2]
        );
        $manager->persist($user);

        // External respondant
        /*$respondent = $this->createUser(
            $this->passwordHasher,
            'external@example.com',
            'User externe',
            'User123!',
            ['ROLE_CONTRIBUTOR'],
            [$r2, $r3]
        );*/

        /* ===================================== Capture element 1 ===================================== */
        // fields
        $f1 = $this->createField(
            new TextAreaField(),
            1,
            'Textarea',
            'Textarea',
            false,
            null
        );
        $manager->persist($f1);

        $f2 = $this->createField(
            new IntegerField(),
            2,
            'Integer',
            'Integer',
            false,
            null
        );
        $manager->persist($f2);

        $f3 = $this->createField(
            new TextField(),
            3,
            'Text',
            'Text',
            false,
            null
        );
        $manager->persist($f3);

        // f4 Decimal
        $f4 = $this->createField(
            new DecimalField(),
            4,
            'Decimal',
            'Decimal',
            false,
            null
        );
        $manager->persist($f4);

        // f5 Date
        $f5 = $this->createField(
            new DateField(),
            5,
            'Date',
            'Date',
            false,
            null
        );
        $manager->persist($f5);

        // f6 Checklist
        $f6 = $this->createField(
            new ChecklistField(),
            5,
            'Checklist',
            'Checklist',
            false,
            null,
            [
                ['label' => 'Option A', 'value' => 'Option A'],
                ['label' => 'Option B', 'value' => 'Option B'],
                ['label' => 'Option C', 'value' => 'Option C'],
            ]
        );
        $manager->persist($f6);

        // f7 SystemComponentCollectionField
        $f7 = $this->createField(
            new SystemComponentCollectionField(),
            6,
            'Composants de SI',
            'Composants de SI',
            false,
            null
        );
        $manager->persist($f7);

        // f8 Link
        $f8 = $this->createField(
            new UrlField(),
            7,
            'Url',
            'Url',
            false,
            null
        );
        $manager->persist($f8);

        // f9 Email
        $f9 = $this->createField(
            new EmailField(),
            7,
            'Email',
            'Email',
            false,
            null
        );
        $manager->persist($f9);

        // capture element
        $flex = (new FlexCaptureElement())
            ->setDescription("Flex capture utilisée pour vérifier que tous les types de fields fonctionnent et s'affichent correctement")
            ->setName('Flex test fields')
            ->setContributor($r1)
            ->setValidator($r3)
            ->addField($f1)
            ->addField($f2)
            ->addField($f3)
            ->addField($f4)
            ->addField($f5)
            ->addField($f6)
            ->addField($f7)
            ->addField($f8)
            ->addField($f9);
        $manager->persist($flex);

        // title
        $title2 = (new Title())
            ->setContent('Test du rendu des types de champs')
            ->setLevel(2);
        $manager->persist($title2);

        // chapter
        $chapter = (new TextChapter())
            ->setTitle($title2)
            ->setTemplateContent('Textarea : [TEXTAREA]
Integer : [INTEGER]
Text : [TEXT]
Decimal : [DECIMAL]
Date : [DATE]
Checklist : [CHECKLIST]
Composants de SI : [COMPOSANTSDESI]
Url : [URL]
Email : [EMAIL]');
        $chapter->setCaptureElement($flex);
        $manager->persist($chapter);

        /* ===================================== Capture element 2 ===================================== */
        // Fields
        // ===== Capture element 2 – Fields =====

        // f10 TextAreaField (labels différents, required = false)
        $f10 = $this->createField(
            new TextAreaField(),
            2,
            'Activité',
            'Décrivez votre activité en quelques lignes',
            false,
            null
        );
        $manager->persist($f10);

        // f20 IntegerField (labels différents, required = true)
        $f20 = $this->createField(
            new IntegerField(),
            3,
            'Nombre de salarié',
            'Combien de salariés travail dans votre entreprise ?',
            true,
            null
        );
        $manager->persist($f20);

        // f30 TextField (labels différents, required = true)
        $f30 = $this->createField(
            new TextField(),
            1,
            'Nom de la société',
            'Quel est le nom de votre société ?', true, null
        );
        $manager->persist($f30);

        // f50 DateField (labels différents, required = false)
        $f50 = $this->createField(
            new DateField(),
            4,
            "Date de début d'activité",
            'Quand avez vous commencez votre activité ?', false, null
        );
        $manager->persist($f50);

        // f60 ChecklistField (labels différents, required = true)
        $f60 = $this->createField(
            new ChecklistField(),
            5,
            'Scope',
            'Cochez les options qui rentrent dans votre activité', true, null,
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

        // capture element
        $flex2 = (new FlexCaptureElement())
            ->setDescription('Recueil des informations classiques sur le compte')
            ->setName('Informations générale')
            ->setContributor($r1)
            ->setValidator($r3)
            ->addField($f10)
            ->addField($f20)
            ->addField($f30)
            ->addField($f50)
            ->addField($f60);
        $manager->persist($flex2);

        // title
        $title = (new Title())
            ->setContent('Activité')
            ->setLevel(2);
        $manager->persist($title);

        // chapter
        $chapter2 = (new TextChapter())
            ->setTitle($title)
            ->setTemplateContent('[NOMDELASOCIETE] est une société de [NOMBREDESALARIE] salariés qui a démarée son activité le [DATEDEDEBUTDACTIVITE].
[ACTIVITE]

Scope :
[SCOPE]');
        $chapter2->setCaptureElement($flex2);
        $manager->persist($chapter2);

        /* ===================================== Capture ===================================== */
        // title
        $CaptureTitle = (new Title())
            ->setContent('Présentation du compte')
            ->setLevel(1);
        $manager->persist($CaptureTitle);

        // condition
        $condition = (new Condition())
            ->setTargetElement($flex)
            ->setSourceElement($flex2)
            ->setSourceField($f20)
            ->setExpectedValue(10);
        $manager->persist($condition);

        // capture
        $capture = (new Capture())
            ->setName('Information du compte')
            ->setDescription('Recueil des informations sur le compte')
            ->addCaptureElement($flex)
            ->addCaptureElement($flex2)
            ->setTitle($CaptureTitle)
            ->setTemplate(true)
            ->addCondition($condition);
        $flex->setCapture($capture);
        $flex2->setCapture($capture);
        $manager->persist($capture);

        /* ===================================== system components ===================================== */
        $scomp1 = (new SystemComponent())
            ->setName('Salesforce')
            ->setType(SystemComponentType::APPLICATION);
        $scomp2 = (new SystemComponent())
            ->setName('Make')
            ->setType(SystemComponentType::APPLICATION);
        $scomp3 = (new SystemComponent())
            ->setName('Microsoft Dynamics')
            ->setType(SystemComponentType::APPLICATION);
        /* ===================================== Information system ===================================== */
        $is1 = $this->createInformationSystem('SI');
        $is1->addSystemComponent($scomp1);
        $is1->addSystemComponent($scomp2);
        $is1->addSystemComponent($scomp3);
        $manager->persist($is1);

        /* ===================================== Contacts ===================================== */

        $contact1 = $this->createContact(
            'jean Dupont',
            'j.dupont@gmail.com',
            'Contact principal'
        );
        $manager->persist($contact1);
        $contact2 = $this->createContact(
            'Elodie Durand',
            'e.durand@gmail.com',
            'Responsable métier'
        );
        $manager->persist($contact2);
        $contact3 = $this->createContact(
            'Damien Gravier',
            'd.gravier@gmail.com',
            'Contact technique'
        );
        $manager->persist($contact3);

        /* ===================================== Account ===================================== */
        $account = (new Account())
            ->setName('Mon client')
            ->setDescription('Une description de mon client')
            ->setInformationSystem($is1)
            ->addContact($contact1)
            ->addContact($contact2)
            ->addContact($contact3);
        $manager->persist($account);

        $manager->flush();
    }

    private function createContact(string $name, string $email, string $function): Contact
    {
        return (new Contact())
            ->setName($name)
            ->setEmail($email)
            ->setFunction($function);
    }

    private function createUser(UserPasswordHasherInterface $passwordHasher, string $email, string $username, string $plainPassword, array $securityRoles = ['ROLE_USER'], ?array $participantRoles = []): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setRoles($securityRoles);

        // Store the hashed password in the entity
        $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));

        /** @var ParticipantRole $participantRole */
        foreach ($participantRoles as $participantRole) {
            $user->addParticipantRole($participantRole);
        }

        return $user;
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

    private function createField(Field $field, int $position, string $name, string $label, bool $required, ?string $help, ?array $choices = null): Field
    {
        $field
            ->setPosition($position)
            ->setName($name)
            ->setLabel($label)
            ->setRequired($required);

        if (null !== $help) {
            $field->setHelp($help);
        }

        if (null !== $choices && $field instanceof ChecklistField) {
            $field->setChoices($choices);
        }

        return $field;
    }
}
