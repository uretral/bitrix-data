<?php

namespace Uretral\BitrixData\RuleInferrers;

use BackedEnum;
use Uretral\BitrixData\Attributes\Validation\ArrayType;
use Uretral\BitrixData\Attributes\Validation\BooleanType;
use Uretral\BitrixData\Attributes\Validation\Enum;
use Uretral\BitrixData\Attributes\Validation\Numeric;
use Uretral\BitrixData\Attributes\Validation\StringType;
use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Validation\PropertyRules;
use Uretral\BitrixData\Support\Validation\RequiringRule;
use Uretral\BitrixData\Support\Validation\ValidationContext;

class BuiltInTypesRuleInferrer implements RuleInferrer
{
    public function handle(
        DataProperty $property,
        PropertyRules $rules,
        ValidationContext $context,
    ): PropertyRules {
        if ($property->type->type->acceptsType('int')) {
            $rules->add(new Numeric());
        }

        if ($property->type->type->acceptsType('string')) {
            $rules->add(new StringType());
        }

        if ($property->type->type->acceptsType('bool')) {
            $rules->removeType(RequiringRule::class);

            $rules->add(new BooleanType());
        }

        if ($property->type->type->acceptsType('float')) {
            $rules->add(new Numeric());
        }

        if ($property->type->type->acceptsType('array')) {
            $rules->add(new ArrayType());
        }

        if ($enumClass = $property->type->type->findAcceptedTypeForBaseType(BackedEnum::class)) {
            $rules->add(new Enum($enumClass));
        }

        return $rules;
    }
}
