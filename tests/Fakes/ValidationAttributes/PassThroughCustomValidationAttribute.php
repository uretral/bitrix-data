<?php

namespace Uretral\BitrixData\Tests\Fakes\ValidationAttributes;

use Attribute;
use Uretral\BitrixData\Attributes\Validation\CustomValidationAttribute;
use Uretral\BitrixData\Support\Validation\ValidationPath;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class PassThroughCustomValidationAttribute extends CustomValidationAttribute
{
    public function __construct(
        private array|object|string $rules,
    ) {
    }

    /**
     * @return array<object|string>|object|string
     */
    public function getRules(ValidationPath $path): array|object|string
    {
        return $this->rules;
    }
}
