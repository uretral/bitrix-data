<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum;

class EnumData extends Data
{
    public function __construct(
        public DummyBackedEnum $enum
    ) {
    }
}
