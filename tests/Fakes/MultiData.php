<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;

class MultiData extends Data
{
    public function __construct(
        public string $first,
        public string $second,
    ) {
    }
}
