<?php

namespace Uretral\BitrixData\Tests\Fakes\CastTransformers;

use Uretral\BitrixData\Casts\Cast;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Transformation\TransformationContext;
use Uretral\BitrixData\Transformers\Transformer;

class FakeCastTransformer implements Cast, Transformer
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return $value;
    }

    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        return $value;
    }
}
