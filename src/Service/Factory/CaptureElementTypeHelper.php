<?php
namespace App\Service\Factory;

use App\Entity\Capture\CaptureElement\CaptureElement;
use Doctrine\ORM\EntityManagerInterface;

final class CaptureElementTypeHelper
{
    public function __construct(private EntityManagerInterface $em) {}

    /** Labels (key : discriminator → value : label) */
    private array $labels = [
        'flex'               => 'Capture libre',
        'system_components'  => 'Composants du système',
    ];

    /** key → FQCN  */
    public function getMap(): array
    {
        $meta = $this->em->getClassMetadata(CaptureElement::class);
        return $meta->discriminatorMap;
    }

    /** synfony ChoiceType : [label => key] */
    public function getChoices(): array
    {
        $choices = [];
        foreach ($this->getMap() as $key => $class) {
            if ($class === CaptureElement::class) { continue; }
            $choices[$this->labelForKey($key)] = $key;
        }
        return $choices;
    }

    /** FQCN form key */
    public function resolveClass(string $key): string
    {
        $map = $this->getMap();
        if (!isset($map[$key])) {
            throw new \InvalidArgumentException("Type inconnu: {$key}");
        }
        return $map[$key];
    }

    /** discriminator from instance */
    public function keyFor(object|string $objectOrClass): string
    {
        $class = is_object($objectOrClass) ? $objectOrClass::class : $objectOrClass;
        $map   = $this->getMap();
        $key   = array_search($class, $map, true);
        if ($key === false) {
            throw new \InvalidArgumentException("Classe non inscrite au DiscriminatorMap: {$class}");
        }
        return $key;
    }

    /** Label from key */
    public function labelForKey(string $key): string
    {
        return $this->labels[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    /** Label from instance */
    public function labelFor(object $element): string
    {
        return $this->labelForKey($this->keyFor($element));
    }
}
