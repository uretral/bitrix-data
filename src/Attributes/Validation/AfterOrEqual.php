<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;
use DateTimeInterface;
use Uretral\BitrixData\Support\Validation\References\FieldReference;
use Uretral\BitrixData\Support\Validation\References\RouteParameterReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class AfterOrEqual extends StringValidationAttribute
{
    public function __construct(protected string|DateTimeInterface|RouteParameterReference|FieldReference $date)
    {
    }

    public static function keyword(): string
    {
        return 'after_or_equal';
    }

    public function parameters(): array
    {
        return [$this->date];
    }

    public static function create(string ...$parameters): static
    {
        return parent::create(
            self::parseDateValue($parameters[0]),
        );
    }
}
