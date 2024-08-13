<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Attributes\Validation\Required;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\Support\Validation\ValidationContext;

class DummyDataWithContextOverwrittenValidationRules extends Data
{
    public string $string;

    #[Required]
    public bool $validate_as_email;

    public static function rules(ValidationContext $context): array
    {
        return $context->payload['validate_as_email'] ?? false
            ? ['string' => ['required', 'string', 'email']]
            : [];
    }
}
