<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;

class SimpleDataWithoutConstructor extends Data
{
    public string $string;

    public static function fromString(string $string)
    {
        $data = new self();

        $data->string = $string;

        return $data;
    }
}
