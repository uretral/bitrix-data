<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;
use Uretral\BitrixData\Support\Validation\References\RouteParameterReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class MinDigits extends StringValidationAttribute
{
    public function __construct(protected int|RouteParameterReference $value)
    {
    }

    public static function keyword(): string
    {
        return 'min_digits';
    }

    public function parameters(): array
    {
        return [$this->value];
    }
}
