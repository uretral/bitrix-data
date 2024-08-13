<?php

namespace Uretral\BitrixData\Exceptions;

use Exception;
use Uretral\BitrixData\Attributes\FromRouteParameterProperty;
use Uretral\BitrixData\Support\DataProperty;

class CannotFillFromRouteParameterPropertyUsingScalarValue extends Exception
{
    public static function create(DataProperty $property, FromRouteParameterProperty $attribute, mixed $value): self
    {
        return new self("Attribute FromRouteParameterProperty cannot be used with scalar route parameters. {$property->className}::{$property->name} is configured to be filled from {$attribute->routeParameter}::{$attribute->property}, but the route parameter has a scalar value ({$value}).");
    }
}
