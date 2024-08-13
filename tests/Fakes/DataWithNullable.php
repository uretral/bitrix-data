<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;

class DataWithNullable extends Data
{
    public function __construct(
        public string $string,
        public ?string $nullableString,
    ) {
    }
}
