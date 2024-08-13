<?php

namespace Uretral\BitrixData\Support\Partials\Segments;

class NestedPartialSegment extends PartialSegment
{
    public function __construct(public readonly string $field)
    {
    }

    public function __toString(): string
    {
        return $this->field;
    }
}
