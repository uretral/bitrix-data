<?php

namespace Uretral\BitrixData\Transformers;

use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Transformation\TransformationContext;

class ArrayableTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): array
    {
        return $value->toArray();
    }
}
