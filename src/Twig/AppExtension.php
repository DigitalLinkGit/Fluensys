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
        $parts = explode('.', $property);
        foreach ($parts as $part) {
            $getter = 'get' . ucfirst($part);
            if (!is_object($object) || !method_exists($object, $getter)) {
                return null;
            }
            $object = $object->$getter();
        }
        return $object;
    }
}
