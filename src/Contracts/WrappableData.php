<?php

namespace Uretral\BitrixData\Contracts;

use Uretral\BitrixData\Support\Wrapping\Wrap;

interface WrappableData extends ContextableData
{
    public function withoutWrapping();

    public function wrap(string $key);

    public function getWrap(): Wrap;
}
