<?php

use Illuminate\Validation\Rules\Enum as BaseEnum;
use Uretral\BitrixData\Attributes\DataCollectionOf;
use Uretral\BitrixData\Attributes\Validation\ArrayType;
use Uretral\BitrixData\Attributes\Validation\BooleanType;
use Uretral\BitrixData\Attributes\Validation\Nullable;
use Uretral\BitrixData\Attributes\Validation\Present;
use Uretral\BitrixData\Attributes\Validation\Required;
use Uretral\BitrixData\Attributes\Validation\RequiredIf;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\Optional;
use Uretral\BitrixData\RuleInferrers\RequiredRuleInferrer;
use Uretral\BitrixData\Support\Validation\PropertyRules;
use Uretral\BitrixData\Support\Validation\RuleDenormalizer;
use Uretral\BitrixData\Support\Validation\ValidationContext;
use Uretral\BitrixData\Support\Validation\ValidationPath;
use Uretral\BitrixData\Tests\Factories\FakeDataStructureFactory;
use Uretral\BitrixData\Tests\Fakes\SimpleData;

/**
 * @return \Uretral\BitrixData\Support\DataProperty;
 */
function getProperty(object $class)
{
    $dataClass = FakeDataStructureFactory::class($class);

    return $dataClass->properties->first();
}

beforeEach(function () {
    $this->inferrer = new RequiredRuleInferrer();
});

it("won't add a required rule when a property is non-nullable", function () {
    $dataProperty = getProperty(new class () extends Data {
        public string $string;
    });

    $rules = $this->inferrer->handle($dataProperty, new PropertyRules(), new ValidationContext([], [], ValidationPath::create(null)));

    expect($rules->all())->toEqualCanonicalizing([new Required()]);
});

it("won't add a required rule when a property is nullable", function () {
    $dataProperty = getProperty(new class () extends Data {
        public ?string $string;
    });

    $rules = $this->inferrer->handle($dataProperty, new PropertyRules(), new ValidationContext([], [], ValidationPath::create(null)));

    expect($rules->all())->toEqualCanonicalizing([]);
});

it("won't add a required rule when a property already contains a required rule", function () {
    $dataProperty = getProperty(new class () extends Data {
        public string $string;
    });

    $rules = $this->inferrer->handle(
        $dataProperty,
        PropertyRules::create()->add(new RequiredIf('bla')),
        new ValidationContext([], [], ValidationPath::create(null))
    );

    expect($rules->all())->toEqualCanonicalizing(['required_if:bla']);
});

it("won't add a required rule when a property already contains a required object rule ", function () {
    $dataProperty = getProperty(new class () extends Data {
        public string $string;
    });

    $rules = $this->inferrer->handle(
        $dataProperty,
        PropertyRules::create()->add(Required::create()),
        new ValidationContext([], [], ValidationPath::create(null))
    );

    expect(app(RuleDenormalizer::class)->execute($rules->all(), ValidationPath::create()))
        ->toEqualCanonicalizing(['required']);
});

it(
    "won't add a required rule when a property already contains a boolean rule",
    function () {
        $dataProperty = getProperty(new class () extends Data {
            public string $string;
        });

        $rules = $this->inferrer->handle(
            $dataProperty,
            PropertyRules::create()->add(BooleanType::create()),
            new ValidationContext([], [], ValidationPath::create(null))
        );

        expect(app(RuleDenormalizer::class)->execute($rules->all(), ValidationPath::create()))
            ->toEqualCanonicalizing([new BooleanType()]);
    }
);

it(
    "won't add a required rule when a property already contains a nullable rule",
    function () {
        $dataProperty = getProperty(new class () extends Data {
            public string $string;
        });

        $rules = $this->inferrer->handle(
            $dataProperty,
            PropertyRules::create()->add(Nullable::create()),
            new ValidationContext([], [], ValidationPath::create(null))
        );

        expect(app(RuleDenormalizer::class)->execute($rules->all(), ValidationPath::create()))
            ->toEqualCanonicalizing([new Nullable()]);
    }
);

it('has support for rules that cannot be converted to string', function () {
    $dataProperty = getProperty(new class () extends Data {
        public string $string;
    });

    $rules = $this->inferrer->handle(
        $dataProperty,
        PropertyRules::create()->add(
            new \Uretral\BitrixData\Attributes\Validation\Enum(new BaseEnum('SomeClass'))
        ),
        new ValidationContext([], [], ValidationPath::create(null))
    );

    expect(app(RuleDenormalizer::class)->execute($rules->all(), ValidationPath::create()))->toEqualCanonicalizing([
        'required', new BaseEnum('SomeClass'),
    ]);
});

it("won't add required to a data collection since it is already present", function () {
    $dataProperty = getProperty(new class () extends Data {
        #[DataCollectionOf(SimpleData::class)]
        public DataCollection $collection;
    });

    $rules = $this->inferrer->handle(
        $dataProperty,
        PropertyRules::create()->add(new Present(), new ArrayType()),
        new ValidationContext([], [], ValidationPath::create(null))
    );

    expect(app(RuleDenormalizer::class)->execute($rules->all(), ValidationPath::create()))
        ->toEqualCanonicalizing(['present', 'array']);
});

it("won't add required rules to undefinable properties", function () {
    $dataProperty = getProperty(new class () extends Data {
        public string|Optional $string;
    });

    $rules = $this->inferrer->handle($dataProperty, [], new ValidationContext([], [], ValidationPath::create(null)));

    expect($rules)->toEqualCanonicalizing([]);
})->throws(TypeError::class);
