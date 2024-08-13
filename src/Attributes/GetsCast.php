<?php

namespace Uretral\BitrixData\Attributes;

use Uretral\BitrixData\Casts\Cast;

interface GetsCast
{
    public function get(): Cast;
}
