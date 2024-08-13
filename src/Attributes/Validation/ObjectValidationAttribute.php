<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Uretral\BitrixData\Support\Validation\References\RouteParameterReference;
use Uretral\BitrixData\Support\Validation\ValidationPath;

abstract class ObjectValidationAttribute extends ValidationAttribute
{
    abstract public function getRule(ValidationPath $path): object|string;

    protected function normalizePossibleRouteReferenceParameter(mixed $parameter): mixed
    {
        if ($parameter instanceof RouteParameterReference) {
            return $parameter->getValue();
        }

        return $parameter;
    }
}
