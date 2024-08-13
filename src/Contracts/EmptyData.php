<?php

namespace Uretral\BitrixData\Contracts;

interface EmptyData
{
    public static function empty(array $extra = []): array;
}
