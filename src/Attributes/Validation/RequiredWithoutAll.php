<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;
use Illuminate\Support\Arr;
use Uretral\BitrixData\Support\Validation\References\FieldReference;
use Uretral\BitrixData\Support\Validation\RequiringRule;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class RequiredWithoutAll extends StringValidationAttribute implements RequiringRule
{
    protected array $fields;

    public function __construct(array|string|FieldReference ...$fields)
    {
        foreach (Arr::flatten($fields) as $field) {
            $this->fields[] = $field instanceof FieldReference ? $field : new FieldReference($field);
        }
    }

    public static function keyword(): string
    {
        return 'required_without_all';
    }

    public function parameters(): array
    {
        return [
            $this->fields,
        ];
    }
}
