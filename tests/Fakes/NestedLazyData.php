<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Lazy;

class NestedLazyData extends Data
{
    public function __construct(
        public SimpleData|Lazy $simple
    ) {
    }

    public static function fromString(string $string): static
    {
        return new self(Lazy::create(fn () => SimpleData::from($string)));
    }
}
