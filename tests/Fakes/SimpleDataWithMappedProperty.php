<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Attributes\MapName;
use Uretral\BitrixData\Data;

class SimpleDataWithMappedProperty extends Data
{
    public function __construct(
        #[MapName('description', 'description')]
        public string $string
    ) {
    }
}
