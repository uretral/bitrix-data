<?php

namespace Uretral\BitrixData\Tests\Fakes\Casts;

use Uretral\BitrixData\Casts\Cast;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;

class ContextAwareCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return $value . '+' . json_encode($properties);
    }
}
