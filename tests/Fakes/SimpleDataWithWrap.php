<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;

class SimpleDataWithWrap extends Data
{
    public function __construct(
        public string $string
    ) {
    }

    public static function fromString(string $string)
    {
        return new self($string);
    }

    public function defaultWrap(): string
    {
        return 'wrap';
    }
}
