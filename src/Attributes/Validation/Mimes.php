<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;
use Illuminate\Support\Arr;
use Uretral\BitrixData\Support\Validation\References\RouteParameterReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Mimes extends StringValidationAttribute
{
    protected array $mimes;

    public function __construct(string|array|RouteParameterReference ...$mimes)
    {
        $this->mimes = Arr::flatten($mimes);
    }

    public static function keyword(): string
    {
        return 'mimes';
    }

    public function parameters(): array
    {
        return [$this->mimes];
    }
}
