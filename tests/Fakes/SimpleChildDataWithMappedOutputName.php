<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Attributes\MapOutputName;
use Uretral\BitrixData\Data;

class SimpleChildDataWithMappedOutputName extends Data
{
    public function __construct(
        public int $id,
        #[MapOutputName('child_amount')]
        public float $amount
    ) {
    }

    public static function allowedRequestExcept(): ?array
    {
        return ['amount'];
    }
}
