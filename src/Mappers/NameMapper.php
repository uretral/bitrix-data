<?php

namespace Uretral\BitrixData\Mappers;

interface NameMapper
{
    public function map(string|int $name): string|int;
}
