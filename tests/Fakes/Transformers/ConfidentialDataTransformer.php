<?php

namespace Uretral\BitrixData\Tests\Fakes\Transformers;

use function collect;

use Uretral\BitrixData\Support\DataProperty;

use Uretral\BitrixData\Support\Transformation\TransformationContext;
use Uretral\BitrixData\Transformers\Transformer;

class ConfidentialDataTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        /** @var \Uretral\BitrixData\Data $value */
        return collect($value->toArray())->map(fn (mixed $value) => 'CONFIDENTIAL')->toArray();
    }
}
