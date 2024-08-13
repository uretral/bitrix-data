<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Attributes\MapName;
use Uretral\BitrixData\Attributes\MapOutputName;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class SimpleDataWithMappedOutputName extends Data
{
    public function __construct(
        public int $id,
        #[MapOutputName('paid_amount')]
        public float $amount,
        public string $anyString,
        public SimpleChildDataWithMappedOutputName $child
    ) {
    }

    public static function allowedRequestExcept(): ?array
    {
        return [
            'amount',
            'anyString',
            'child',
        ];
    }
}
