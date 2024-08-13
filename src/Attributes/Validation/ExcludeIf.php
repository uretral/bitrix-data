<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Attribute;
use BackedEnum;
use Uretral\BitrixData\Support\Validation\References\FieldReference;
use Uretral\BitrixData\Support\Validation\References\RouteParameterReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class ExcludeIf extends StringValidationAttribute
{
    protected FieldReference $field;

    public function __construct(
        string|FieldReference                                              $field,
        protected string|int|float|bool|BackedEnum|RouteParameterReference $value
    ) {
        $this->field = $this->parseFieldReference($field);
    }

    public static function keyword(): string
    {
        return 'exclude_if';
    }

    public static function create(string ...$parameters): static
    {
        return parent::create(
            $parameters[0],
            self::parseBooleanValue($parameters[1]),
        );
    }

    public function parameters(): array
    {
        return [
            $this->field,
            $this->value,
        ];
    }
}
