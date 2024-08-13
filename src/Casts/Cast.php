<?php

namespace Uretral\BitrixData\Casts;

use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;

interface Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed;
}
