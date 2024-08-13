<?php

namespace Uretral\BitrixData\Tests\Fakes\Castables;

use Uretral\BitrixData\Casts\Cast;
use Uretral\BitrixData\Casts\Castable;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;

class SimpleCastable implements Castable
{
    public function __construct(public string $value)
    {
    }

    public static function dataCastUsing(...$arguments): Cast
    {
        return new class () implements Cast {
            public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
            {
                return new SimpleCastable($value);
            }
        };
    }
}
