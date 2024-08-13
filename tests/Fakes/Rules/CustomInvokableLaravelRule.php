<?php

namespace Uretral\BitrixData\Tests\Fakes\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class CustomInvokableLaravelRule implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {
    }
}
