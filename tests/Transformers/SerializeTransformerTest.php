<?php

namespace Uretral\BitrixData\Tests\Transformers;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Support\Transformation\TransformationContextFactory;
use Uretral\BitrixData\Tests\Factories\FakeDataStructureFactory;
use Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum;
use Uretral\BitrixData\Transformers\SerializeTransformer;

it('can transform using a serializer', function () {
    $transformer = new SerializeTransformer();

    $class = new class () extends Data {
        public DummyBackedEnum $enum = DummyBackedEnum::FOO;
    };

    expect(
        $transformer->transform(
            FakeDataStructureFactory::property($class, 'enum'),
            $class->enum,
            TransformationContextFactory::create()->get($class)
        )
    )->toEqual(serialize(DummyBackedEnum::FOO));
});
