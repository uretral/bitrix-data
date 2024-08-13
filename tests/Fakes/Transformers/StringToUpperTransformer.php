<?php

namespace Uretral\BitrixData\Tests\Fakes\Transformers;

use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Transformation\TransformationContext;
use Uretral\BitrixData\Transformers\Transformer;

class StringToUpperTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): string
    {
        return strtoupper($value);
    }
}

;
