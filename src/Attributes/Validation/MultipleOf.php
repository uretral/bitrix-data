<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;
use Uretral\BitrixData\Support\Validation\References\RouteParameterReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class MultipleOf extends StringValidationAttribute
{
    public function __construct(protected int|float|RouteParameterReference $value)
    {
    }

    public static function keyword(): string
    {
        return 'multiple_of';
    }

    public function parameters(): array
    {
        return [$this->value];
    }
}
