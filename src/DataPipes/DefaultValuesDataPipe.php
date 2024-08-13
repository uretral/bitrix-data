<?php

namespace Uretral\BitrixData\DataPipes;

use Uretral\BitrixData\Optional;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataClass;

class DefaultValuesDataPipe implements DataPipe
{
    public function handle(
        mixed $payload,
        DataClass $class,
        array $properties,
        CreationContext $creationContext
    ): array {
        foreach ($class->properties as $name => $property) {
            if(array_key_exists($name, $properties)) {
                continue;
            }

            if ($property->hasDefaultValue) {
                $properties[$name] = $property->defaultValue;

                continue;
            }

            if ($property->type->isOptional) {
                $properties[$name] = Optional::create();

                continue;
            }

            if ($property->type->isNullable) {
                $properties[$name] = null;

                continue;
            }
        }

        return $properties;
    }
}
