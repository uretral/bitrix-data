<?php

namespace Uretral\BitrixData\Attributes;

use Attribute;
use Uretral\BitrixData\Contracts\BaseData;
use Uretral\BitrixData\Exceptions\CannotFindDataClass;

#[Attribute(Attribute::TARGET_PROPERTY)]
class DataCollectionOf
{
    public function __construct(
        /** @var class-string<\Uretral\BitrixData\Contracts\BaseData> $class */
        public string $class
    ) {
        if (! is_subclass_of($this->class, BaseData::class)) {
            throw new CannotFindDataClass("Class {$this->class} given does not implement `BaseData::class`");
        }
    }
}
