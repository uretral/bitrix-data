<?php

namespace Uretral\BitrixData\Transformers;

use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Transformation\TransformationContext;

interface Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed;
}
