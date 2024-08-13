<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Attributes\Validation\RequiredIf;
use Uretral\BitrixData\Data;

class DataWithReferenceFieldValidationAttribute extends Data
{
    public bool $check_string;

    #[RequiredIf('check_string', true)]
    public string $string;
}
