<?php

namespace Uretral\BitrixData\Support\Validation\References;

use Uretral\BitrixData\Support\Validation\ValidationPath;

class FieldReference
{
    public function __construct(
        public readonly string $name,
        public readonly bool $fromRoot = false,
    ) {
    }

    public function getValue(ValidationPath $path): string
    {
        return $this->fromRoot
            ? $this->name
            : $path->property($this->name)->get();
    }
}
