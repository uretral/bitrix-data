<?php

namespace Uretral\BitrixData\RuleInferrers;

use Uretral\BitrixData\Attributes\Validation\Nullable;
use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Validation\PropertyRules;
use Uretral\BitrixData\Support\Validation\ValidationContext;

class NullableRuleInferrer implements RuleInferrer
{
    public function handle(
        DataProperty $property,
        PropertyRules $rules,
        ValidationContext $context,
    ): PropertyRules {
        if ($property->type->isNullable && ! $rules->hasType(Nullable::class)) {
            $rules->prepend(new Nullable());
        }

        return $rules;
    }
}
