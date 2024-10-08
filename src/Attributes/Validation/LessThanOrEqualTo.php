<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;
use Uretral\BitrixData\Support\Validation\References\FieldReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class LessThanOrEqualTo extends StringValidationAttribute
{
    protected int|float|FieldReference $field;

    public function __construct(
        int|float|string|FieldReference $field,
    ) {
        $this->field = is_numeric($field) ? $field : $this->parseFieldReference($field);
    }


    public static function keyword(): string
    {
        return 'lte';
    }

    public function parameters(): array
    {
        return [$this->field];
    }
}
