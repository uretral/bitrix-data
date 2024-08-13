<?php

namespace Uretral\BitrixData\Tests\Fakes\Transformers;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Transformation\TransformationContext;
use Uretral\BitrixData\Transformers\Transformer;

class ConfidentialDataCollectionTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        /** @var array $value */
        return array_map(fn (Data $data) => (new ConfidentialDataTransformer())->transform($property, $data, $context), $value);
    }
}
