<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Dto;

class SimpleDto extends Dto
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
