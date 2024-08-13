<?php

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Support\Annotations\DataIterableAnnotation;
use Uretral\BitrixData\Support\Annotations\DataIterableAnnotationReader;
use Uretral\BitrixData\Tests\Fakes\CollectionDataAnnotationsData;
use Uretral\BitrixData\Tests\Fakes\CollectionNonDataAnnotationsData;
use Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum;
use Uretral\BitrixData\Tests\Fakes\Error;
use Uretral\BitrixData\Tests\Fakes\SimpleData;

it(
    'can get the data class for a data collection by annotation',
    function (string $property, ?DataIterableAnnotation $expected) {
        $annotations = app(DataIterableAnnotationReader::class)->getForProperty(new ReflectionProperty(CollectionDataAnnotationsData::class, $property));

        expect($annotations)->toEqual($expected);
    }
)->with(function () {
    yield 'propertyA' => [
        'property' => 'propertyA',
        'expected' => new DataIterableAnnotation(SimpleData::class, isData: true),
    ];

    yield 'propertyB' => [
        'property' => 'propertyB',
        'expected' => new DataIterableAnnotation(SimpleData::class, isData: true),
    ];

    yield 'propertyC' => [
        'property' => 'propertyC',
        'expected' => new DataIterableAnnotation(SimpleData::class, isData: true),
    ];

    yield 'propertyD' => [
        'property' => 'propertyD',
        'expected' => new DataIterableAnnotation(SimpleData::class, isData: true),
    ];

    yield 'propertyE' => [
        'property' => 'propertyE',
        'expected' => new DataIterableAnnotation(SimpleData::class, isData: true),
    ];

    yield 'propertyF' => [
        'property' => 'propertyF',
        'expected' => new DataIterableAnnotation(SimpleData::class, isData: true),
    ];

    yield 'propertyG' => [
        'property' => 'propertyG',
        'expected' => new DataIterableAnnotation(SimpleData::class, isData: true),
    ];

    yield 'propertyH' => [
        'property' => 'propertyH',
        'expected' => null, // Attribute
    ];

    yield 'propertyI' => [
        'property' => 'propertyI',
        'expected' => null, // Invalid definition
    ];

    yield 'propertyJ' => [
        'property' => 'propertyJ',
        'expected' => null, // No definition
    ];

    yield 'propertyK' => [
        'property' => 'propertyK',
        'expected' => new DataIterableAnnotation(SimpleData::class, isData: true),
    ];

    yield 'propertyL' => [
        'property' => 'propertyL',
        'expected' => new DataIterableAnnotation(SimpleData::class, isData: true),
    ];

    yield 'propertyM' => [
        'property' => 'propertyM',
        'expected' => new DataIterableAnnotation(SimpleData::class, isData: true),
    ];

    yield 'propertyU' => [
        'property' => 'propertyU',
        'expected' => new DataIterableAnnotation(SimpleData::class, isData: true),
    ];
});

it('can get the data class for a data collection by class annotation', function () {
    $annotations = app(DataIterableAnnotationReader::class)->getForClass(new ReflectionClass(CollectionDataAnnotationsData::class));

    expect($annotations)->toEqualCanonicalizing([
        'propertyN' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'propertyN'),
        'propertyO' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'propertyO'),
        'propertyP' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'propertyP'),
        'propertyQ' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'propertyQ'),
        'propertyR' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'propertyR'),
        'propertyS' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'propertyS'),
        'propertyT' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'propertyT'),
    ]);
});

it('can get data class for a data collection by method annotation', function () {
    $annotations = app(DataIterableAnnotationReader::class)->getForMethod(new ReflectionMethod(CollectionDataAnnotationsData::class, 'method'));

    expect($annotations)->toEqualCanonicalizing([
        'paramA' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'paramA'),
        'paramB' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'paramB'),
        'paramC' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'paramC'),
        'paramD' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'paramD'),
        'paramE' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'paramE'),
        'paramF' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'paramF'),
        'paramG' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'paramG'),
        'paramH' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'paramH'),
        'paramJ' => new DataIterableAnnotation(SimpleData::class, isData: true, keyType: 'int', property: 'paramJ'),
        'paramI' => new DataIterableAnnotation(SimpleData::class, isData: true, keyType: 'int', property: 'paramI'),
        'paramK' => new DataIterableAnnotation(SimpleData::class, isData: true, property: 'paramK'),
    ]);
});

it(
    'can get the iterable class for a collection by annotation',
    function (string $property, ?DataIterableAnnotation $expected) {
        $annotations = app(DataIterableAnnotationReader::class)->getForProperty(new ReflectionProperty(CollectionNonDataAnnotationsData::class, $property));

        expect($annotations)->toEqual($expected);
    }
)->with(function () {
    yield 'propertyA' => [
        'property' => 'propertyA',
        'expected' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false),
    ];

    yield 'propertyB' => [
        'property' => 'propertyB',
        'expected' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false),
    ];

    yield 'propertyC' => [
        'property' => 'propertyC',
        'expected' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false),
    ];

    yield 'propertyD' => [
        'property' => 'propertyD',
        'expected' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false),
    ];

    yield 'propertyE' => [
        'property' => 'propertyE',
        'expected' => new DataIterableAnnotation('string', isData: false),
    ];

    yield 'propertyF' => [
        'property' => 'propertyF',
        'expected' => new DataIterableAnnotation('string', isData: false),
    ];

    yield 'propertyG' => [
        'property' => 'propertyG',
        'expected' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false),
    ];

    yield 'propertyH' => [
        'property' => 'propertyH',
        'expected' => null, // Invalid
    ];

    yield 'propertyI' => [
        'property' => 'propertyI',
        'expected' => null, // No definition
    ];

    yield 'propertyJ' => [
        'property' => 'propertyJ',
        'expected' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false),
    ];

    yield 'propertyK' => [
        'property' => 'propertyK',
        'expected' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false),
    ];

    yield 'propertyL' => [
        'property' => 'propertyL',
        'expected' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false),
    ];

    yield 'propertyP' => [
        'property' => 'propertyP',
        'expected' => new DataIterableAnnotation(Error::class, isData: true),
    ];

    yield 'propertyR' => [
        'property' => 'propertyR',
        'expected' => new DataIterableAnnotation(Error::class, isData: true),
    ];
});

it('can get the iterable class for a collection by class annotation', function () {
    $annotations = app(DataIterableAnnotationReader::class)->getForClass(new ReflectionClass(CollectionNonDataAnnotationsData::class));

    expect($annotations)->toEqualCanonicalizing([
        'propertyM' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'propertyM'),
        'propertyN' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'propertyN'),
        'propertyO' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'propertyO'),
        'propertyQ' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'propertyQ'),
    ]);
});

it('can get iterable class for a data by method annotation', function () {
    $annotations = app(DataIterableAnnotationReader::class)->getForMethod(new ReflectionMethod(CollectionNonDataAnnotationsData::class, 'method'));

    expect($annotations)->toEqualCanonicalizing([
        'paramA' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'paramA'),
        'paramB' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'paramB'),
        'paramC' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'paramC'),
        'paramD' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'paramD'),
        'paramE' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'paramE'),
        'paramF' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'paramF'),
        'paramG' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'paramG'),
        'paramH' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'paramH'),
        'paramJ' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, keyType: 'int', property: 'paramJ'),
        'paramI' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, keyType: 'int', property: 'paramI'),
        'paramK' => new DataIterableAnnotation(DummyBackedEnum::class, isData: false, property: 'paramK'),
    ]);
});

it('will always prefer the data version of the annotation in unions', function () {
    $dataClass = new class () extends Data {
        /** @var array<string|Uretral\BitrixData\Tests\Fakes\SimpleData> */
        public array $property;
    };

    $annotations = app(DataIterableAnnotationReader::class)->getForProperty(
        new ReflectionProperty($dataClass::class, 'property')
    );

    expect($annotations)->toEqual(new DataIterableAnnotation(SimpleData::class, isData: true));
});

it('will recognize default PHP types', function (string $type) {
    $dataClass = new class () extends Data {
        /** @var array<string> */
        public array $string;

        /** @var array<int> */
        public array $int;

        /** @var array<float> */
        public array $float;

        /** @var array<bool> */
        public array $bool;

        /** @var array<mixed> */
        public array $mixed;

        /** @var array<array> */
        public array $array;

        /** @var array<iterable> */
        public array $iterable;

        /** @var array<object> */
        public array $object;

        /** @var array<callable> */
        public array $callable;
    };

    expect(
        app(DataIterableAnnotationReader::class)->getForProperty(
            new ReflectionProperty($dataClass::class, $type)
        )
    )->toEqual(new DataIterableAnnotation($type, isData: false));
})->with([
    'string' => ['string'],
    'int' => ['int'],
    'float' => ['float'],
    'bool' => ['bool'],
    'mixed' => ['mixed'],
    'array' => ['array'],
    'iterable' => ['iterable'],
    'object' => ['object'],
    'callable' => ['callable'],
]);

it('can recognize the key of an iterable', function (
    string $property,
    string $keyType,
) {
    $dataClass = new class () extends Data {
        /** @var array<string, float> */
        public array $propertyA;

        /** @var array<int, float> */
        public array $propertyB;

        /** @var array<array-key, float> */
        public array $propertyC;

        /** @var array<int|string, float> */
        public array $propertyD;

        /** @var array<string|int, float> */
        public array $propertyE;

        /** @var array<int , float> */
        public array $propertyF;
    };

    expect(
        app(DataIterableAnnotationReader::class)->getForProperty(
            new ReflectionProperty($dataClass::class, $property)
        )
    )->toEqual(new DataIterableAnnotation('float', isData: false, keyType: $keyType));
})->with([
    'string key' => ['propertyA', 'string'],
    'int key' => ['propertyB', 'int'],
    'array key' => ['propertyC', 'array-key'],
    'int|string key' => ['propertyD', 'int|string'],
    'string|int key' => ['propertyE', 'string|int'],
    'spaces' => ['propertyF', 'int'],
]);
