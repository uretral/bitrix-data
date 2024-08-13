<?php

namespace Uretral\BitrixData\Transformers;

use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Transformation\TransformationContext;

class EnumTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): string|int
    {
        return $value->value;
    }
}
