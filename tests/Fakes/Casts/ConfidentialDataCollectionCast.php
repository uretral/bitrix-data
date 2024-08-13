<?php

namespace Uretral\BitrixData\Tests\Fakes\Casts;

use Uretral\BitrixData\Casts\Cast;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Tests\Fakes\SimpleData;

class ConfidentialDataCollectionCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): array
    {
        return array_map(fn () => SimpleData::from('CONFIDENTIAL'), $value);
    }
}
