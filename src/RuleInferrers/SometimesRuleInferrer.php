<?php

namespace Uretral\BitrixData\RuleInferrers;

use Uretral\BitrixData\Attributes\Validation\Sometimes;
use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Validation\PropertyRules;
use Uretral\BitrixData\Support\Validation\ValidationContext;

class SometimesRuleInferrer implements RuleInferrer
{
    public function handle(
        DataProperty $property,
        PropertyRules $rules,
        ValidationContext $context,
    ): PropertyRules {
        if ($property->type->isOptional && ! $rules->hasType(Sometimes::class)) {
            $rules->prepend(new Sometimes());
        }

        return $rules;
    }
}
