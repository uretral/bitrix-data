<?php

use Illuminate\Support\Collection;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\Support\Transformation\TransformationContextFactory;
use Uretral\BitrixData\Tests\Factories\FakeDataStructureFactory;
use Uretral\BitrixData\Transformers\ArrayableTransformer;

it('can transform an arrayable', function () {
    $transformer = new ArrayableTransformer();

    $class = new class (new Collection(['A', 'B'])) extends Data {
        public function __construct(
            public Collection $arrayable,
        ) {
        }
    };

    expect(
        $transformer->transform(
            FakeDataStructureFactory::property($class, 'arrayable'),
            $class->arrayable,
            TransformationContextFactory::create()->get($class)
        )
    )->toEqual(['A', 'B']);
});
