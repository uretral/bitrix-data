<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;

class NestedNullableData extends Data
{
    public function __construct(
        public ?SimpleData $nested
    ) {
    }
}
