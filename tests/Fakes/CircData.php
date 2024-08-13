<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;

class CircData extends Data
{
    public function __construct(
        public string $string,
        public ?UlarData $ular,
    ) {
    }
}
