<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;

class NestedData extends Data
{
    public function __construct(
        public SimpleData $simple
    ) {
    }

    public function toUserDefinedToArray(): array
    {
        return [
            'simple' => $this->simple->toUserDefinedToArray(),
        ];
    }
}
