<?php

namespace Uretral\BitrixData\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class FromRouteParameter
{
    public function __construct(
        public string $routeParameter,
        public bool $replaceWhenPresentInBody = true,
    ) {
    }
}
