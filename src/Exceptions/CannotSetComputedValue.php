<?php

namespace Uretral\BitrixData\Exceptions;

use Exception;
use Uretral\BitrixData\Support\DataProperty;

class CannotSetComputedValue extends Exception
{
    public static function create(DataProperty $property): self
    {
        return new self("Cannot set property {$property->className}::\${$property->name} because it is a computed property.");
    }
}
