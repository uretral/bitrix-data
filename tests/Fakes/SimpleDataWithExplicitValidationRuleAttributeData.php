<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Attributes\Validation\Email;
use Uretral\BitrixData\Data;

class SimpleDataWithExplicitValidationRuleAttributeData extends Data
{
    public function __construct(
        #[Email]
        public string $email,
    ) {
    }
}
