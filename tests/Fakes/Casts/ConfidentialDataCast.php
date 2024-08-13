<?php

namespace Uretral\BitrixData\Tests\Fakes\Casts;

use Uretral\BitrixData\Casts\Cast;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Tests\Fakes\SimpleData;

class ConfidentialDataCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): SimpleData
    {
        return SimpleData::from('CONFIDENTIAL');
    }
}
