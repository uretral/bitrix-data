<?php

namespace Uretral\BitrixData\Tests\Fakes\Casts;

use Uretral\BitrixData\Casts\Cast;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;

class ValueDefinedCast implements Cast
{
    public function __construct(
        private mixed $value
    ) {
    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return $this->value;
    }
}
