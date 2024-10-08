<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;
use Uretral\BitrixData\Support\Validation\References\RouteParameterReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Regex extends StringValidationAttribute
{
    public function __construct(protected string|RouteParameterReference $pattern)
    {
    }

    public static function keyword(): string
    {
        return 'regex';
    }

    public function parameters(): array
    {
        return [
            $this->pattern,
        ];
    }
}
