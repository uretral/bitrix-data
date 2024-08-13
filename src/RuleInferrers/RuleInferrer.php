<?php

namespace Uretral\BitrixData\RuleInferrers;

use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Validation\PropertyRules;
use Uretral\BitrixData\Support\Validation\ValidationContext;

interface RuleInferrer
{
    public function handle(DataProperty $property, PropertyRules $rules, ValidationContext $context): PropertyRules;
}
