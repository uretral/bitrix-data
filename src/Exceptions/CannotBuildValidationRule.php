<?php

namespace Uretral\BitrixData\Exceptions;

use Exception;

class CannotBuildValidationRule extends Exception
{
    public static function create(string $message): self
    {
        return new self($message);
    }
}
