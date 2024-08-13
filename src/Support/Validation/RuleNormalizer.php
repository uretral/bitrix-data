<?php

namespace Uretral\BitrixData\Support\Validation;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\Rule as RuleContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Dimensions as DimensionsRule;
use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Validation\Rules\ExcludeIf as ExcludeIfRule;
use Illuminate\Validation\Rules\Exists as ExistsRule;
use Illuminate\Validation\Rules\In as InRule;
use Illuminate\Validation\Rules\NotIn as NotInRule;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\Rules\ProhibitedIf as ProhibitedIfRule;
use Illuminate\Validation\Rules\RequiredIf as RequiredIfRule;
use Illuminate\Validation\Rules\Unique as UniqueRule;
use Uretral\BitrixData\Attributes\Validation\Dimensions;
use Uretral\BitrixData\Attributes\Validation\Enum;
use Uretral\BitrixData\Attributes\Validation\Exclude;
use Uretral\BitrixData\Attributes\Validation\Exists;
use Uretral\BitrixData\Attributes\Validation\In;
use Uretral\BitrixData\Attributes\Validation\NotIn;
use Uretral\BitrixData\Attributes\Validation\Password;
use Uretral\BitrixData\Attributes\Validation\Prohibited;
use Uretral\BitrixData\Attributes\Validation\Required;
use Uretral\BitrixData\Attributes\Validation\Rule;
use Uretral\BitrixData\Attributes\Validation\Unique;
use Throwable;

class RuleNormalizer
{
    public function __construct(protected ValidationRuleFactory $ruleFactory)
    {
    }

    /** @return \Uretral\BitrixData\Support\Validation\ValidationRule[] */
    public function execute(mixed $rule): array
    {
        if (is_array($rule)) {
            return $this->resolveArrayRule($rule);
        }

        if (is_string($rule)) {
            return $this->resolveStringRule($rule);
        }

        if ($rule instanceof Rule) {
            return $this->execute($rule->get());
        }

        if ($rule instanceof ValidationRule) {
            return [$rule];
        }

        $objectRule = match (true) {
            $rule instanceof DimensionsRule => new Dimensions(rule: $rule),
            $rule instanceof EnumRule => new Enum($rule),
            $rule instanceof ExcludeIfRule => new Exclude($rule),
            $rule instanceof ExistsRule => new Exists(rule: $rule),
            $rule instanceof InRule => new In($rule),
            $rule instanceof NotInRule => new NotIn($rule),
            $rule instanceof PasswordRule => new Password(rule: $rule),
            $rule instanceof ProhibitedIfRule => new Prohibited($rule),
            $rule instanceof RequiredIfRule => new Required($rule),
            $rule instanceof UniqueRule => new Unique(rule: $rule),
            default => null,
        };

        if ($objectRule) {
            return [$objectRule];
        }

        if ($rule instanceof RuleContract | $rule instanceof InvokableRule) {
            return [new Rule($rule)];
        }

        return [new Rule($rule)];
    }

    protected function resolveArrayRule(array $rules): array
    {
        return Arr::flatten(array_map(
            fn (mixed $rule) => $this->execute($rule),
            $rules
        ));
    }

    protected function resolveStringRule(string $rule): array
    {
        $rules = [];

        $subRules = Str::contains($rule, 'regex:') ? [$rule] : explode('|', $rule);
        foreach ($subRules as $subRule) {
            try {
                $rules[] = $this->ruleFactory->create($subRule);
            } catch (Throwable $t) {
                $rules[] = new Rule($subRule);
            }
        }

        return $rules;
    }
}
