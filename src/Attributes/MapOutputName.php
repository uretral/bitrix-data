<?php

namespace Uretral\BitrixData\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class MapOutputName
{
    public function __construct(public string|int $output)
    {
    }
}
