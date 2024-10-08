<?php

namespace Uretral\BitrixData\RuleInferrers;

use Uretral\BitrixData\Attributes\Validation\BooleanType;
use Uretral\BitrixData\Attributes\Validation\Nullable;
use Uretral\BitrixData\Attributes\Validation\Present;
use Uretral\BitrixData\Attributes\Validation\Required;
use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Validation\PropertyRules;
use Uretral\BitrixData\Support\Validation\RequiringRule;
use Uretral\BitrixData\Support\Validation\ValidationContext;

class RequiredRuleInferrer implements RuleInferrer
{
    public function handle(
        DataProperty $property,
        PropertyRules $rules,
        ValidationContext $context,
    ): PropertyRules {
        if ($this->shouldAddRule($property, $rules)) {
            $rules->prepend(new Required());
        }

        return $rules;
    }

    protected function shouldAddRule(DataProperty $property, PropertyRules $rules): bool
    {
        if ($property->type->isNullable || $property->type->isOptional) {
            return false;
        }

        if ($property->type->kind->isDataCollectable() && $rules->hasType(Present::class)) {
            return false;
        }

        if ($rules->hasType(BooleanType::class)) {
            return false;
        }

        if ($rules->hasType(Nullable::class)) {
            return false;
        }

        if ($rules->hasType(RequiringRule::class)) {
            return false;
        }

        return true;
    }
}
