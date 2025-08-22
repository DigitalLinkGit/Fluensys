<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('attribute_chain', [$this, 'attributeChain']),
        ];
    }

    public function attributeChain($object, string $property)
    {
        foreach (explode('.', $property) as $part) {
            if (!is_object($object)) return null;
            $uc = ucfirst($part);
            foreach (["get$uc", "is$uc", "has$uc"] as $m) {
                if (method_exists($object, $m)) { $object = $object->$m(); continue 2; }
            }
            if (property_exists($object, $part)) { $object = $object->$part; continue; }
            return null;
        }
        return $object;
    }

}
