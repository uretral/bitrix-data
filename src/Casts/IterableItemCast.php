<?php

namespace Uretral\BitrixData\Casts;

use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;

interface IterableItemCast
{
    public function castIterableItem(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed;
}
