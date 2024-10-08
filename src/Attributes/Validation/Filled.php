<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Filled extends StringValidationAttribute
{
    public static function keyword(): string
    {
        return 'filled';
    }

    public function parameters(): array
    {
        return [];
    }
}
