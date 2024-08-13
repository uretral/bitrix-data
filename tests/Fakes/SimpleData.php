<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;

class SimpleData extends Data
{
    public function __construct(
        public string $string
    ) {
    }

    public static function fromString(string $string): self
    {
        return new self($string);
    }

    public function toUserDefinedToArray(): array
    {
        return [
            'string' => $this->string,
        ];
    }
}
