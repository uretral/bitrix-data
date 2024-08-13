<?php

use Uretral\BitrixData\Attributes\Computed;
use Uretral\BitrixData\Attributes\DataCollectionOf;
use Uretral\BitrixData\Attributes\Hidden;
use Uretral\BitrixData\Attributes\MapInputName;
use Uretral\BitrixData\Attributes\MapOutputName;
use Uretral\BitrixData\Attributes\WithCast;
use Uretral\BitrixData\Attributes\WithCastAndTransformer;
use Uretral\BitrixData\Attributes\WithoutValidation;
use Uretral\BitrixData\Attributes\WithTransformer;
use Uretral\BitrixData\Casts\DateTimeInterfaceCast;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Factories\DataPropertyFactory;
use Uretral\BitrixData\Tests\Fakes\CastTransformers\FakeCastTransformer;
use Uretral\BitrixData\Tests\Fakes\Models\DummyModel;
use Uretral\BitrixData\Tests\Fakes\SimpleData;
use Uretral\BitrixData\Transformers\DateTimeInterfaceTransformer;

function resolveHelper(
    object $class,
    bool $hasDefaultValue = false,
    mixed $defaultValue = null
): DataProperty {
    $reflectionProperty = new ReflectionProperty($class, 'property');
    $reflectionClass = new ReflectionClass($class);

    return app(DataPropertyFactory::class)->build($reflectionProperty, $reflectionClass, $hasDefaultValue, $defaultValue);
}

it('can get the cast attribute with arguments', function () {
    $helper = resolveHelper(new class () {
        #[WithCast(DateTimeInterfaceCast::class, 'd-m-y')]
        public SimpleData $property;
    });

    expect($helper->cast)->toEqual(new DateTimeInterfaceCast('d-m-y'));
});

it('can get the transformer attribute', function () {
    $helper = resolveHelper(new class () {
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public SimpleData $property;
    });

    expect($helper->transformer)->toEqual(new DateTimeInterfaceTransformer());
});

it('can get the transformer attribute with arguments', function () {
    $helper = resolveHelper(new class () {
        #[WithTransformer(DateTimeInterfaceTransformer::class, 'd-m-y')]
        public SimpleData $property;
    });

    expect($helper->transformer)->toEqual(new DateTimeInterfaceTransformer('d-m-y'));
});

it('can get the cast with transformer attribute', function () {
    $helper = resolveHelper(new class () {
        #[WithCastAndTransformer(FakeCastTransformer::class)]
        public SimpleData $property;
    });

    expect($helper->transformer)->toEqual(new FakeCastTransformer());
    expect($helper->cast)->toEqual(new FakeCastTransformer());
});

it('can get the mapped input name', function () {
    $helper = resolveHelper(new class () {
        #[MapInputName('other')]
        public SimpleData $property;
    });

    expect($helper->inputMappedName)->toEqual('other');
});

it('can get the mapped output name', function () {
    $helper = resolveHelper(new class () {
        #[MapOutputName('other')]
        public SimpleData $property;
    });

    expect($helper->outputMappedName)->toEqual('other');
});

it('can get all attributes', function () {
    $helper = resolveHelper(new class () {
        #[MapInputName('other')]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[DataCollectionOf(SimpleData::class)]
        public DataCollection $property;
    });

    expect($helper->attributes)->toHaveCount(4);
});

it('can get the default value', function () {
    $helper = resolveHelper(new class () {
        public string $property;
    });

    expect($helper->hasDefaultValue)->toBeFalse();

    $helper = resolveHelper(new class () {
        public string $property = 'hello';
    });

    expect($helper)
        ->hasDefaultValue->toBeTrue()
        ->defaultValue->toEqual('hello');
});

it('can check if the property is promoted', function () {
    $helper = resolveHelper(new class ('') {
        public function __construct(
            public string $property,
        ) {
        }
    });

    expect($helper->isPromoted)->toBeTrue();

    $helper = resolveHelper(new class () {
        public string $property;
    });

    expect($helper->isPromoted)->toBeFalse();
});

it('can check if a property should be validated', function () {
    expect(
        resolveHelper(new class () {
            public string $property;
        })->validate
    )->toBeTrue();

    expect(
        resolveHelper(new class () {
            #[WithoutValidation]
            public string $property;
        })->validate
    )->toBeFalse();

    expect(
        resolveHelper(new class () {
            #[Computed]
            public string $property;
        })->validate
    )->toBeFalse();
});

it('can check if a property is computed', function () {
    expect(
        resolveHelper(new class () {
            public string $property;
        })->computed
    )->toBeFalse();

    expect(
        resolveHelper(new class () {
            #[Computed]
            public string $property;
        })->computed
    )->toBeTrue();
});

it('can check if a property is hidden', function () {
    expect(
        resolveHelper(new class () {
            public string $property;
        })->hidden
    )->toBeFalse();

    expect(
        resolveHelper(new class () {
            #[Hidden]
            public string $property;
        })->hidden
    )->toBeTrue();
});

it('wont throw an error if non existing attribute is used on a data class property', function () {
    expect(NonExistingPropertyAttributeData::from(['property' => 'hello'])->property)->toEqual('hello')
        ->and(PhpStormAttributeData::from(['property' => 'hello'])->property)->toEqual('hello')
        ->and(PhpStormAttributeData::from('{"property": "hello"}')->property)->toEqual('hello')
        ->and(PhpStormAttributeData::from((object) ['property' => 'hello'])->property)->toEqual('hello')
        ->and(ModelWithPhpStormAttributePropertyData::from((new DummyModel())->fill(['id' => 1]))->id)->toEqual(1)
        ->and(ModelWithPromotedPhpStormAttributePropertyData::from((new DummyModel())->fill(['id' => 1]))->id)->toEqual(1);
});

class NonExistingPropertyAttributeData extends Data
{
    #[\Foo\Bar]
    public readonly string $property;

    public function __construct(string $property)
    {
        $this->property = $property;
    }
}

class PhpStormAttributeData extends Data
{
    #[\JetBrains\PhpStorm\Immutable]
    public readonly string $property;

    public function __construct(string $property)
    {
        $this->property = $property;
    }
}

class PromotedPhpStormAttributeData extends Data
{
    public function __construct(
        #[\JetBrains\PhpStorm\Immutable]
        public readonly string $property
    ) {
        //
    }
}

class ModelWithPhpStormAttributePropertyData extends Data
{
    #[\JetBrains\PhpStorm\Immutable]
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function fromDummyModel(DummyModel $model)
    {
        return new self($model->id);
    }
}

class ModelWithPromotedPhpStormAttributePropertyData extends Data
{
    public function __construct(
        #[\JetBrains\PhpStorm\Immutable]
        public int $id
    ) {
    }

    public static function fromDummyModel(DummyModel $model)
    {
        return new self($model->id);
    }
}
