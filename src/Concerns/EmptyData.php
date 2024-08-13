<?php

namespace Uretral\BitrixData\Concerns;

use Illuminate\Container\Container;
use Uretral\BitrixData\Resolvers\EmptyDataResolver;

trait EmptyData
{
    public static function empty(array $extra = []): array
    {
        return  Container::getInstance()->make(EmptyDataResolver::class)->execute(static::class, $extra);
    }
}
