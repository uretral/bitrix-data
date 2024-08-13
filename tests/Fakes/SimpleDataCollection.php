<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\DataCollection;

class SimpleDataCollection extends DataCollection
{
    public function toJson($options = 0): string
    {
        return parent::toJson(JSON_PRETTY_PRINT);
    }
}
