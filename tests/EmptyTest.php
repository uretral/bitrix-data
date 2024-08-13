<?php

use Illuminate\Support\Collection;
use Uretral\BitrixData\Attributes\DataCollectionOf;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\Lazy;
use Uretral\BitrixData\Tests\Fakes\SimpleData;

it('can get the empty version of a data object', function () {
    $dataClass = new class () extends Data {
        public string $property;

        public string|Lazy $lazyProperty;

        public array $array;

        public Collection $collection;

        #[DataCollectionOf(SimpleData::class)]
        public DataCollection $dataCollection;

        public SimpleData $data;

        public Lazy|SimpleData $lazyData;

        public bool $defaultProperty = true;
    };

    expect($dataClass::empty())->toMatchArray([
        'property' => null,
        'lazyProperty' => null,
        'array' => [],
        'collection' => [],
        'dataCollection' => [],
        'data' => [
            'string' => null,
        ],
        'lazyData' => [
            'string' => null,
        ],
        'defaultProperty' => true,
    ]);
});

it('can overwrite properties in an empty version of a data object', function () {
    expect(SimpleData::empty())->toMatchArray([
        'string' => null,
    ]);

    expect(SimpleData::empty(['string' => 'Ruben']))->toMatchArray([
        'string' => 'Ruben',
    ]);
});
