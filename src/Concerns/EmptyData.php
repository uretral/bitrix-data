<?php

namespace Spatie\LaravelData\Concerns;

use Illuminate\Container\Container;
use Spatie\LaravelData\Resolvers\EmptyDataResolver;

trait EmptyData
{
    public static function empty(array $extra = []): array
    {
        return  Container::getInstance()->make(EmptyDataResolver::class)->execute(static::class, $extra);
    }
}
