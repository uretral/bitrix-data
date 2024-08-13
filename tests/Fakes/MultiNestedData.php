<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Attributes\DataCollectionOf;
use Uretral\BitrixData\Data;

class MultiNestedData extends Data
{
    public function __construct(
        public NestedData $nested,
        #[DataCollectionOf(NestedData::class)]
        public array $nestedCollection
    ) {
    }
}
