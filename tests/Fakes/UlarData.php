<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;

class UlarData extends Data
{
    public function __construct(
        public string $string,
        public ?CircData $circ,
    ) {
    }
}
