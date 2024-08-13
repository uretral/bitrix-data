<?php

namespace Uretral\BitrixData\Attributes\Validation;

use Uretral\BitrixData\Support\Validation\ValidationPath;
use Uretral\BitrixData\Support\Validation\ValidationRule;

abstract class CustomValidationAttribute extends ValidationRule
{
    /**
     * @return array<object|string>|object|string
     */
    abstract public function getRules(ValidationPath $path): array|object|string;
}
