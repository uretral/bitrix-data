<?php

use Uretral\BitrixData\Attributes\DataCollectionOf;
use Uretral\BitrixData\Attributes\MapInputName;
use Uretral\BitrixData\Attributes\MapName;
use Uretral\BitrixData\Attributes\MapOutputName;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\Mappers\CamelCaseMapper;
use Uretral\BitrixData\Mappers\ProvidedNameMapper;
use Uretral\BitrixData\Mappers\SnakeCaseMapper;
use Uretral\BitrixData\Mappers\StudlyCaseMapper;
use Uretral\BitrixData\Support\Transformation\TransformationContextFactory;
use Uretral\BitrixData\Tests\Fakes\DataWithMapper;
use Uretral\BitrixData\Tests\Fakes\SimpleData;
use Uretral\BitrixData\Tests\Fakes\SimpleDataWithMappedProperty;

it('can map property names when transforming', function () {
    $data = new SimpleDataWithMappedProperty('hello');
    $dataCollection = SimpleDataWithMappedProperty::collect([
        ['description' => 'never'],
        ['description' => 'gonna'],
        ['description' => 'give'],
        ['description' => 'you'],
        ['description' => 'up'],
    ]);

    $dataClass = new class ('hello', $data, $data, $dataCollection, $dataCollection) extends Data {
        public function __construct(
            #[MapOutputName('property')]
            public string $string,
            public SimpleDataWithMappedProperty $nested,
            #[MapOutputName('nested_other')]
            public SimpleDataWithMappedProperty $nested_renamed,
            #[DataCollectionOf(SimpleDataWithMappedProperty::class)]
            public array $nested_collection,
            #[
                MapOutputName('nested_other_collection'),
                DataCollectionOf(SimpleDataWithMappedProperty::class)
            ]
            public array $nested_renamed_collection,
        ) {
        }
    };

    expect($dataClass->toArray())->toMatchArray([
        'property' => 'hello',
        'nested' => [
            'description' => 'hello',
        ],
        'nested_other' => [
            'description' => 'hello',
        ],
        'nested_collection' => [
            ['description' => 'never'],
            ['description' => 'gonna'],
            ['description' => 'give'],
            ['description' => 'you'],
            ['description' => 'up'],
        ],
        'nested_other_collection' => [
            ['description' => 'never'],
            ['description' => 'gonna'],
            ['description' => 'give'],
            ['description' => 'you'],
            ['description' => 'up'],
        ],
    ]);
});

it('can map the property names for the whole class using one attribute when transforming', function () {
    $data = DataWithMapper::from([
        'cased_property' => 'We are the knights who say, ni!',
        'data_cased_property' =>
            ['string' => 'Bring us a, shrubbery!'],
        'data_collection_cased_property' => [
            ['string' => 'One that looks nice!'],
            ['string' => 'But not too expensive!'],
        ],
    ]);

    expect($data->toArray())->toMatchArray([
        'cased_property' => 'We are the knights who say, ni!',
        'data_cased_property' =>
            ['string' => 'Bring us a, shrubbery!'],
        'data_collection_cased_property' => [
            ['string' => 'One that looks nice!'],
            ['string' => 'But not too expensive!'],
        ],
    ]);
});

it('can transform the data object without mapping', function () {
    $data = new class ('Freek') extends Data {
        public function __construct(
            #[MapOutputName('snake_name')]
            public string $camelName
        ) {
        }
    };

    expect($data)->transform(TransformationContextFactory::create()->withPropertyNameMapping(false))
        ->toMatchArray([
            'camelName' => 'Freek',
        ]);
});

it('can map an input property using string when creating', function () {
    $dataClass = new class () extends Data {
        #[MapInputName('something')]
        public string $mapped;
    };

    $data = $dataClass::from([
        'something' => 'We are the knights who say, ni!',
    ]);

    expect($data->mapped)->toEqual('We are the knights who say, ni!');
});

it('can map an input property in nested objects using strings when creating', function () {
    $dataClass = new class () extends Data {
        #[MapInputName('nested.something')]
        public string $mapped;
    };

    $data = $dataClass::from([
        'nested' => ['something' => 'We are the knights who say, ni!'],
    ]);

    expect($data->mapped)->toEqual('We are the knights who say, ni!');
});

it('replaces properties when a mapped alternative exists when creating', function () {
    $dataClass = new class () extends Data {
        #[MapInputName('something')]
        public string $mapped;
    };

    $data = $dataClass::from([
        'mapped' => 'We are the knights who say, ni!',
        'something' => 'Bring us a, shrubbery!',
    ]);

    expect($data->mapped)->toEqual('Bring us a, shrubbery!');
});

it('skips properties it cannot find when creating', function () {
    $dataClass = new class () extends Data {
        #[MapInputName('something')]
        public string $mapped;
    };

    $data = $dataClass::from([
        'mapped' => 'We are the knights who say, ni!',
    ]);

    expect($data->mapped)->toEqual('We are the knights who say, ni!');
});


it('can use integers to map properties when creating', function () {
    $dataClass = new class () extends Data {
        #[MapInputName(1)]
        public string $mapped;
    };

    $data = $dataClass::from([
        'We are the knights who say, ni!',
        'Bring us a, shrubbery!',
    ]);

    expect($data->mapped)->toEqual('Bring us a, shrubbery!');
});

it('can use integers to map properties in nested data when creating', function () {
    $dataClass = new class () extends Data {
        #[MapInputName('1.0')]
        public string $mapped;
    };

    $data = $dataClass::from([
        ['We are the knights who say, ni!'],
        ['Bring us a, shrubbery!'],
    ]);

    expect($data->mapped)->toEqual('Bring us a, shrubbery!');
});

it('can combine integers and strings to map properties when creating', function () {
    $dataClass = new class () extends Data {
        #[MapInputName('lines.1')]
        public string $mapped;
    };

    $data = $dataClass::from([
        'lines' => [
            'We are the knights who say, ni!',
            'Bring us a, shrubbery!',
        ],
    ]);

    expect($data->mapped)->toEqual('Bring us a, shrubbery!');
});

it('can use a special mapping class which converts property names between standards', function () {
    $dataClass = new class () extends Data {
        #[MapInputName(SnakeCaseMapper::class)]
        public string $mappedLine;
    };

    $data = $dataClass::from([
        'mapped_line' => 'We are the knights who say, ni!',
    ]);

    expect($data->mappedLine)->toEqual('We are the knights who say, ni!');
});

it('can use mapped properties to magically create data', function () {
    $dataClass = new class () extends Data {
        #[MapInputName('something')]
        public SimpleData $mapped;
    };

    $value = collect([
        'something' => 'We are the knights who say, ni!',
    ]);

    $data = $dataClass::from($value);

    expect($data->mapped)->toEqual(
        SimpleData::from('We are the knights who say, ni!')
    );
});

it('can use mapped properties (nested) to magically create data', function () {
    $dataClass = new class () extends Data {
        #[MapInputName('something')]
        public SimpleDataWithMappedProperty $mapped;
    };

    $value = collect([
        'something' => [
            'description' => 'We are the knights who say, ni!',
        ],
    ]);

    $data = $dataClass::from($value);

    expect($data->mapped)->toEqual(
        new SimpleDataWithMappedProperty('We are the knights who say, ni!')
    );
});

it('can map properties when creating a collection of data objects', function () {
    $dataClass = new class () extends Data {
        #[MapInputName('something'), DataCollectionOf(SimpleData::class)]
        public array $mapped;
    };

    $value = collect([
        'something' => [
            'We are the knights who say, ni!',
            'Bring us a, shrubbery!',
        ],
    ]);

    $data = $dataClass::from($value);

    expect($data->mapped)->toEqual(
        SimpleData::collect([
            'We are the knights who say, ni!',
            'Bring us a, shrubbery!',
        ])
    );
});

it('can map properties when creating a (nested) collection of data objects', function () {
    $dataClass = new class () extends Data {
        #[MapInputName('something'), DataCollectionOf(SimpleDataWithMappedProperty::class)]
        public array $mapped;
    };

    $value = collect([
        'something' => [
            ['description' => 'We are the knights who say, ni!'],
            ['description' => 'Bring us a, shrubbery!'],
        ],
    ]);

    $data = $dataClass::from($value);

    expect($data->mapped)->toEqual(
        SimpleDataWithMappedProperty::collect([
            ['description' => 'We are the knights who say, ni!'],
            ['description' => 'Bring us a, shrubbery!'],
        ])
    );
});

it('can use one attribute on the class to map properties when creating', function () {
    $data = DataWithMapper::from([
        'cased_property' => 'We are the knights who say, ni!',
        'data_cased_property' =>
            ['string' => 'Bring us a, shrubbery!'],
        'data_collection_cased_property' => [
            ['string' => 'One that looks nice!'],
            ['string' => 'But not too expensive!'],
        ],
    ]);

    expect($data)
        ->casedProperty->toEqual('We are the knights who say, ni!')
        ->dataCasedProperty->toEqual(SimpleData::from('Bring us a, shrubbery!'))
        ->dataCollectionCasedProperty->toEqual(SimpleData::collect([
            'One that looks nice!',
            'But not too expensive!',
        ]));
});

it('has a mappers built in', function () {
    $data = new class () extends Data {
        #[MapName(CamelCaseMapper::class)]
        public string $camel_case = 'camelCase';

        #[MapName(SnakeCaseMapper::class)]
        public string $snakeCase = 'snake_case';

        #[MapName(StudlyCaseMapper::class)]
        public string $studly_case = 'StudlyCase';

        #[MapName(new ProvidedNameMapper('i_provided'))]
        public string $provided = 'provided';
    };

    expect($data->toArray())->toEqual([
        'camelCase' => 'camelCase',
        'snake_case' => 'snake_case',
        'StudlyCase' => 'StudlyCase',
        'i_provided' => 'provided',
    ]);

    expect($data::from([
        'camelCase' => 'camelCase',
        'snake_case' => 'snake_case',
        'StudlyCase' => 'StudlyCase',
        'i_provided' => 'provided',
    ]))
        ->camel_case->toBe('camelCase')
        ->snakeCase->toBe('snake_case')
        ->studly_case->toBe('StudlyCase')
        ->provided->toBe('provided');
});
