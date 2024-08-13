<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Alpha extends StringValidationAttribute
{
    public static function keyword(): string
    {
        return 'alpha';
    }

    public function parameters(): array
    {
        return [];
    }
}
