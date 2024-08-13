<?php

namespace Uretral\BitrixData\RuleInferrers;

use Uretral\BitrixData\Attributes\Validation\Present;
use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Validation\PropertyRules;
use Uretral\BitrixData\Support\Validation\RequiringRule;
use Uretral\BitrixData\Support\Validation\RuleNormalizer;
use Uretral\BitrixData\Support\Validation\ValidationContext;
use Uretral\BitrixData\Support\Validation\ValidationRule;

class AttributesRuleInferrer implements RuleInferrer
{
    public function __construct(protected RuleNormalizer $rulesDenormalizer)
    {
    }

    public function handle(
        DataProperty $property,
        PropertyRules $rules,
        ValidationContext $context,
    ): PropertyRules {
        $property
            ->attributes
            ->filter(fn (object $attribute) => $attribute instanceof ValidationRule)
            ->each(function (ValidationRule $rule) use ($rules) {
                if($rule instanceof Present && $rules->hasType(RequiringRule::class)) {
                    $rules->removeType(RequiringRule::class);
                }

                $rules->add(
                    ...$this->rulesDenormalizer->execute($rule)
                );
            });

        return $rules;
    }
}
