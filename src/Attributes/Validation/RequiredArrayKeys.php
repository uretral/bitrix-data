<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;
use Illuminate\Support\Arr;
use Uretral\BitrixData\Support\Validation\References\RouteParameterReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class RequiredArrayKeys extends StringValidationAttribute
{
    protected string|array $values;

    public function __construct(string|array|RouteParameterReference ...$values)
    {
        $this->values = Arr::flatten($values);
    }

    public static function keyword(): string
    {
        return 'required_array_keys';
    }

    public function parameters(): array
    {
        return [$this->values];
    }
}
