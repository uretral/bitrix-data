<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Lowercase extends StringValidationAttribute
{
    public static function keyword(): string
    {
        return 'lowercase';
    }

    public function parameters(): array
    {
        return [];
    }
}
