<?php

namespace Uretral\BitrixData\Tests\Fakes\Casts;

use Uretral\BitrixData\Casts\Cast;
use Uretral\BitrixData\Casts\IterableItemCast;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;

class MeaningOfLifeCast implements Cast, IterableItemCast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): int
    {
        return 42;
    }

    public function castIterableItem(DataProperty $property, mixed $value, array $properties, CreationContext $context): int
    {
        return 42;
    }
}
