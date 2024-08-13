<?php

namespace Uretral\BitrixData\Support\Partials\Segments;

class AllPartialSegment extends PartialSegment
{
    public function __toString(): string
    {
        return '*';
    }
}
