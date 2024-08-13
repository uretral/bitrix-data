<?php

namespace Uretral\BitrixData\Tests\Fakes\Casts;

use Uretral\BitrixData\Casts\Cast;
use Uretral\BitrixData\Casts\IterableItemCast;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;

class StringToUpperCast implements Cast, IterableItemCast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): string
    {
        return $this->castValue($value);
    }

    public function castIterableItem(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return $this->castValue($value);
    }

    private function castValue(mixed $value): string
    {
        return strtoupper($value);
    }
}
