<?php

use Illuminate\Contracts\Validation\Rule as CustomRuleContract;
use Illuminate\Validation\Rules\Exists as BaseExists;
use Uretral\BitrixData\Attributes\Validation\Dimensions;
use Uretral\BitrixData\Attributes\Validation\Exists;
use Uretral\BitrixData\Attributes\Validation\Min;
use Uretral\BitrixData\Attributes\Validation\Regex;
use Uretral\BitrixData\Attributes\Validation\Required;
use Uretral\BitrixData\Attributes\Validation\Rule;
use Uretral\BitrixData\Support\Validation\RuleNormalizer;

beforeEach(function () {
    $this->mapper = resolve(RuleNormalizer::class);
});

it('can map string rules')
    ->expect(fn () => $this->mapper->execute(['required']))
    ->toEqual([new Required()]);

it('can map string rules with arguments')
    ->expect(fn () => $this->mapper->execute(['exists:users']))
    ->toEqual([new Exists(rule: new BaseExists('users'))]);

it('can map string rules with key-value arguments')
    ->expect(fn () => $this->mapper->execute(['dimensions:min_width=100,min_height=200']))
    ->toEqual([new Dimensions(minWidth: 100, minHeight: 200)]);

it('can map string rules with regex')
    ->expect(fn () => $this->mapper->execute(['regex:/test|ok/']))
    ->toEqual([new Regex('/test|ok/')]);

it('can map multiple rules')
    ->expect(fn () => $this->mapper->execute(['required', 'min:0']))
    ->toEqual([new Required(), new Min(0)]);

it('can map multiple concatenated rules')
    ->expect(fn () => $this->mapper->execute(['required|min:0']))
    ->toEqual([new Required(), new Min(0)]);

it('can map faulty rules')
    ->expect(fn () => $this->mapper->execute(['min:']))
    ->toEqual([new Rule('min:')]);

it('can map Laravel rule objects')
    ->expect(fn () => $this->mapper->execute([new BaseExists('users')]))
    ->toEqual([(new Exists(rule: new BaseExists('users')))]);

it('can map a custom Laravel rule objects', function () {
    $rule = new class () implements CustomRuleContract {
        public function passes($attribute, $value)
        {
        }

        public function message()
        {
        }
    };

    expect($this->mapper->execute([$rule]))->toEqual([new Rule($rule)]);
});
