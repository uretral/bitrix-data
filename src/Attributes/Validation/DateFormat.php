<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;
use Illuminate\Support\Arr;
use Uretral\BitrixData\Support\Validation\References\RouteParameterReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class DateFormat extends StringValidationAttribute
{
    protected string|array $format;

    public function __construct(string|array|RouteParameterReference ...$format)
    {
        $this->format = Arr::flatten($format);
    }

    public static function keyword(): string
    {
        return 'date_format';
    }

    public function parameters(): array
    {
        return [$this->format];
    }
}
