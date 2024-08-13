<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Resource;

class SimpleResource extends Resource
{
    public function __construct(
        public string $string
    ) {
    }

    public static function fromString(string $string): self
    {
        return new self($string);
    }
}
