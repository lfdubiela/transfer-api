<?php
declare(strict_types=1);

namespace App\Domain\Common;

use ReflectionClass;
use ReflectionProperty;

/**
 * Comportamento padrão para Classes de Dominio
 *
 * Trait JsonSerialize
 *
 * @package App\Domain\Common
 */
trait JsonSerialize
{
    public function jsonSerialize()
    {
        if (!empty($this->value)) {
            return $this->value;
        }

        $reflect = new ReflectionClass($this);
        $props   = $reflect->getProperties(ReflectionProperty::IS_STATIC | ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE);

        $propsIterator = function () use ($props) {
            foreach ($props as $prop) {
                yield $prop->getName() => $this->{$prop->getName()};
            }
        };

        return iterator_to_array($propsIterator());
    }
}
