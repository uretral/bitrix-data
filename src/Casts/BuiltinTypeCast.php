<?php

namespace Uretral\BitrixData\Casts;

use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;

class BuiltinTypeCast implements Cast, IterableItemCast
{
    /**
     * @param 'bool'|'int'|'float'|'array'|'string' $type
     */
    public function __construct(
        protected string $type,
    ) {
    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return $this->runCast($value);
    }

    public function castIterableItem(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return $this->runCast($value);
    }

    protected function runCast(mixed $value): mixed
    {
        return match ($this->type) {
            'bool' => (bool) $value,
            'int' => (int) $value,
            'float' => (float) $value,
            'array' => (array) $value,
            'string' => (string) $value,
        };
    }
}
