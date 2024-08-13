<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;

class DataWithMultipleArgumentCreationMethod extends Data
{
    public function __construct(
        public string $concatenated,
    ) {
    }

    public static function fromMultiple(string $string, int $number)
    {
        return new self("{$string}_{$number}");
    }
}
