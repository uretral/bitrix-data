<?php

namespace Uretral\BitrixData\Support;

class DataParameter
{
    public function __construct(
        public readonly string $name,
        public readonly bool $isPromoted,
        public readonly bool $hasDefaultValue,
        public readonly mixed $defaultValue,
        public readonly DataType $type,
    ) {
    }
}
