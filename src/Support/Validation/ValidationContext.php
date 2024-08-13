<?php

namespace Uretral\BitrixData\Support\Validation;

class ValidationContext
{
    public function __construct(
        public mixed $payload,
        public mixed $fullPayload,
        public ValidationPath $path,
    ) {
    }
}
