<?php

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\postJson;

use Uretral\BitrixData\Attributes\Computed;
use Uretral\BitrixData\Attributes\DataCollectionOf;
use Uretral\BitrixData\Attributes\Validation\Min;
use Uretral\BitrixData\Attributes\WithCast;
use Uretral\BitrixData\Attributes\WithCastable;
use Uretral\BitrixData\Casts\DateTimeInterfaceCast;
use Uretral\BitrixData\Casts\Uncastable;
use Uretral\BitrixData\Concerns\WithDeprecatedCollectionMethod;
use Uretral\BitrixData\Contracts\DeprecatedData as DeprecatedDataContract;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\DataPipeline;
use Uretral\BitrixData\DataPipes\DataPipe;
use Uretral\BitrixData\Exceptions\CannotCreateData;
use Uretral\BitrixData\Exceptions\CannotSetComputedValue;
use Uretral\BitrixData\Lazy;
use Uretral\BitrixData\Optional;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataClass;
use Uretral\BitrixData\Tests\Fakes\Castables\SimpleCastable;
use Uretral\BitrixData\Tests\Fakes\Casts\ConfidentialDataCast;
use Uretral\BitrixData\Tests\Fakes\Casts\ConfidentialDataCollectionCast;
use Uretral\BitrixData\Tests\Fakes\Casts\ContextAwareCast;
use Uretral\BitrixData\Tests\Fakes\Casts\MeaningOfLifeCast;
use Uretral\BitrixData\Tests\Fakes\Casts\StringToUpperCast;
use Uretral\BitrixData\Tests\Fakes\Casts\ValueDefinedCast;
use Uretral\BitrixData\Tests\Fakes\Collections\CustomCollection;
use Uretral\BitrixData\Tests\Fakes\ComplicatedData;
use Uretral\BitrixData\Tests\Fakes\DataCollections\CustomCursorPaginatedDataCollection;
use Uretral\BitrixData\Tests\Fakes\DataCollections\CustomDataCollection;
use Uretral\BitrixData\Tests\Fakes\DataCollections\CustomPaginatedDataCollection;
use Uretral\BitrixData\Tests\Fakes\EnumData;
use Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum;
use Uretral\BitrixData\Tests\Fakes\ModelData;
use Uretral\BitrixData\Tests\Fakes\Models\DummyModel;
use Uretral\BitrixData\Tests\Fakes\MultiData;
use Uretral\BitrixData\Tests\Fakes\NestedData;
use Uretral\BitrixData\Tests\Fakes\NestedLazyData;
use Uretral\BitrixData\Tests\Fakes\NestedModelCollectionData;
use Uretral\BitrixData\Tests\Fakes\NestedModelData;
use Uretral\BitrixData\Tests\Fakes\SimpleData;
use Uretral\BitrixData\Tests\Fakes\SimpleDataWithoutConstructor;

it('can use default types to create data objects', function () {
    $data = ComplicatedData::from([
        'withoutType' => 42,
        'int' => 42,
        'bool' => true,
        'float' => 3.14,
        'string' => 'Hello world',
        'array' => [1, 1, 2, 3, 5, 8],
        'nullable' => null,
        'mixed' => 42,
        'explicitCast' => '16-06-1994',
        'defaultCast' => '1994-05-16T12:00:00+01:00',
        'nestedData' => [
            'string' => 'hello',
        ],
        'nestedCollection' => [
            ['string' => 'never'],
            ['string' => 'gonna'],
            ['string' => 'give'],
            ['string' => 'you'],
            ['string' => 'up'],
        ],
        'nestedArray' => [
            ['string' => 'never'],
            ['string' => 'gonna'],
            ['string' => 'give'],
            ['string' => 'you'],
            ['string' => 'up'],
        ],
    ]);

    expect($data)->toBeInstanceOf(ComplicatedData::class)
        ->withoutType->toEqual(42)
        ->int->toEqual(42)
        ->bool->toBeTrue()
        ->float->toEqual(3.14)
        ->string->toEqual('Hello world')
        ->array->toEqual([1, 1, 2, 3, 5, 8])
        ->nullable->toBeNull()
        ->undefinable->toBeInstanceOf(Optional::class)
        ->mixed->toEqual(42)
        ->defaultCast->toEqual(DateTime::createFromFormat(DATE_ATOM, '1994-05-16T12:00:00+01:00'))
        ->explicitCast->toEqual(CarbonImmutable::createFromFormat('d-m-Y', '16-06-1994'))
        ->nestedData->toEqual(SimpleData::from('hello'))
        ->nestedCollection->toEqual(SimpleData::collect([
            SimpleData::from('never'),
            SimpleData::from('gonna'),
            SimpleData::from('give'),
            SimpleData::from('you'),
            SimpleData::from('up'),
        ], DataCollection::class))
        ->nestedArray->toEqual(SimpleData::collect([
            SimpleData::from('never'),
            SimpleData::from('gonna'),
            SimpleData::from('give'),
            SimpleData::from('you'),
            SimpleData::from('up'),
        ]));
});

it("won't cast a property that is already in the correct type", function () {
    $data = ComplicatedData::from([
        'withoutType' => 42,
        'int' => 42,
        'bool' => true,
        'float' => 3.14,
        'string' => 'Hello world',
        'array' => [1, 1, 2, 3, 5, 8],
        'nullable' => null,
        'mixed' => 42,
        'explicitCast' => DateTime::createFromFormat('d-m-Y', '16-06-1994'),
        'defaultCast' => DateTime::createFromFormat(DATE_ATOM, '1994-05-16T12:00:00+02:00'),
        'nestedData' => SimpleData::from('hello'),
        'nestedCollection' => SimpleData::collect([
            'never', 'gonna', 'give', 'you', 'up',
        ], DataCollection::class),
        'nestedArray' => SimpleData::collect([
            'never', 'gonna', 'give', 'you', 'up',
        ]),
    ]);

    expect($data)->toBeInstanceOf(ComplicatedData::class)
        ->withoutType->toEqual(42)
        ->int->toEqual(42)
        ->bool->toBeTrue()
        ->float->toEqual(3.14)
        ->string->toEqual('Hello world')
        ->array->toEqual([1, 1, 2, 3, 5, 8])
        ->nullable->toBeNull()
        ->mixed->toBe(42)
        ->defaultCast->toEqual(DateTime::createFromFormat(DATE_ATOM, '1994-05-16T12:00:00+02:00'))
        ->explicitCast->toEqual(DateTime::createFromFormat('d-m-Y', '16-06-1994'))
        ->nestedData->toEqual(SimpleData::from('hello'))
        ->nestedCollection->toEqual(SimpleData::collect([
            SimpleData::from('never'),
            SimpleData::from('gonna'),
            SimpleData::from('give'),
            SimpleData::from('you'),
            SimpleData::from('up'),
        ], DataCollection::class))
        ->nestedArray->toEqual(SimpleData::collect([
            SimpleData::from('never'),
            SimpleData::from('gonna'),
            SimpleData::from('give'),
            SimpleData::from('you'),
            SimpleData::from('up'),
        ]));
});

it('allows creating data objects using Lazy', function () {
    $data = NestedLazyData::from([
        'simple' => Lazy::create(fn () => SimpleData::from('Hello')),
    ]);

    expect($data->simple)
        ->toBeInstanceOf(Lazy::class)
        ->toEqual(Lazy::create(fn () => SimpleData::from('Hello')));
});

it('can set a custom cast', function () {
    $dataClass = new class () extends Data {
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public DateTimeImmutable $date;
    };

    $data = $dataClass::from([
        'date' => '2022-01-18',
    ]);

    expect($data->date)
        ->toBeInstanceOf(DateTimeImmutable::class)
        ->toEqual(DateTimeImmutable::createFromFormat('Y-m-d', '2022-01-18'));
});

it('allows casting of enums', function () {
    $data = EnumData::from([
        'enum' => 'foo',
    ]);

    expect($data->enum)
        ->toBeInstanceOf(DummyBackedEnum::class)
        ->toEqual(DummyBackedEnum::FOO);
});

it('can optionally create data', function () {
    expect(SimpleData::optional())->toBeNull();
    expect(SimpleData::optional(null))->toBeNull();
    expect(new SimpleData('Hello world'))->toEqual(
        SimpleData::optional(['string' => 'Hello world'])
    );
});

it('can create a data model without constructor', function () {
    expect(SimpleDataWithoutConstructor::fromString('Hello'))
        ->toEqual(SimpleDataWithoutConstructor::from('Hello'));

    expect(SimpleDataWithoutConstructor::fromString('Hello'))
        ->toEqual(SimpleDataWithoutConstructor::from([
            'string' => 'Hello',
        ]));

    expect(
        new DataCollection(SimpleDataWithoutConstructor::class, [
            SimpleDataWithoutConstructor::fromString('Hello'),
            SimpleDataWithoutConstructor::fromString('World'),
        ])
    )
        ->toEqual(SimpleDataWithoutConstructor::collect(['Hello', 'World'], DataCollection::class));
});

it('can create a data object from a model', function () {
    DummyModel::migrate();

    $model = DummyModel::create([
        'string' => 'test',
        'boolean' => true,
        'date' => CarbonImmutable::create(2020, 05, 16, 12, 00, 00),
        'nullable_date' => null,
        'nullable_optional_date' => null,
    ]);

    $dataClass = new class () extends Data {
        public string $string;

        public bool $boolean;

        public Carbon $date;

        public ?Carbon $nullable_date;

        public Optional|Carbon $optional_date;

        public Optional|null|Carbon $nullable_optional_date;
    };

    $data = $dataClass::from(DummyModel::findOrFail($model->id));

    expect($data)
        ->string->toEqual('test')
        ->boolean->toBeTrue()
        ->nullable_date->toBeNull()
        ->optional_date->toBeInstanceOf(Optional::class)
        ->nullable_optional_date->toBeNull()
        ->and(CarbonImmutable::create(2020, 05, 16, 12, 00, 00)->eq($data->date))->toBeTrue();
});


it('can create a data object from a stdClass object', function () {
    $object = (object) [
        'string' => 'test',
        'boolean' => true,
        'date' => CarbonImmutable::create(2020, 05, 16, 12, 00, 00),
        'nullable_date' => null,
    ];

    $dataClass = new class () extends Data {
        public string $string;

        public bool $boolean;

        public CarbonImmutable $date;

        public ?Carbon $nullable_date;
    };

    $data = $dataClass::from($object);

    expect($data)
        ->string->toEqual('test')
        ->boolean->toBeTrue()
        ->nullable_date->toBeNull()
        ->and(CarbonImmutable::create(2020, 05, 16, 12, 00, 00)->eq($data->date))->toBeTrue();
});

it('has support for readonly properties', function () {
    $dataClass = new class ('') extends Data {
        public function __construct(
            public readonly string $string
        ) {
        }
    };

    $data = $dataClass::from(['string' => 'Hello world']);

    expect($data)->toBeInstanceOf($dataClass::class)
        ->and($data->string)->toEqual('Hello world');
});


it('has support for intersection types', function () {
    $collection = collect(['a', 'b', 'c']);

    $dataClass = new class () extends Data {
        public Arrayable & \Countable $intersection;
    };

    $data = $dataClass::from(['intersection' => $collection]);

    expect($data)->toBeInstanceOf($dataClass::class)
        ->and($data->intersection)->toEqual($collection);
});

it(
    'can construct a data object with both constructor promoted and default properties',
    function () {
        $dataClass = new class ('') extends Data {
            public string $property;

            public function __construct(
                public string $promoted_property,
            ) {
            }
        };

        $data = $dataClass::from([
            'property' => 'A',
            'promoted_property' => 'B',
        ]);

        expect($data)
            ->property->toEqual('A')
            ->promoted_property->toEqual('B');
    }
);

it('can construct a data object with default values', function () {
    $dataClass = new class ('', '') extends Data {
        public string $property;

        public string $default_property = 'Hello';

        public function __construct(
            public string $promoted_property,
            public string $default_promoted_property = 'Hello Again',
        ) {
        }
    };

    $data = $dataClass::from([
        'property' => 'Test',
        'promoted_property' => 'Test Again',
    ]);

    expect($data)
        ->property->toEqual('Test')
        ->promoted_property->toEqual('Test Again')
        ->default_property->toEqual('Hello')
        ->default_promoted_property->toEqual('Hello Again');
});


it('can construct a data object with default values and overwrite them', function () {
    $dataClass = new class ('', '') extends Data {
        public string $property;

        public string $default_property = 'Hello';

        public function __construct(
            public string $promoted_property,
            public string $default_promoted_property = 'Hello Again',
        ) {
        }
    };

    $data = $dataClass::from([
        'property' => 'Test',
        'default_property' => 'Test',
        'promoted_property' => 'Test Again',
        'default_promoted_property' => 'Test Again',
    ]);

    expect($data)
        ->property->toEqual('Test')
        ->promoted_property->toEqual('Test Again')
        ->default_property->toEqual('Test')
        ->default_promoted_property->toEqual('Test Again');
});

it('can manually set values in the constructor', function () {
    $dataClass = new class ('', '') extends Data {
        public string $member;

        public string $other_member;

        public string $member_with_default = 'default';

        public string $member_to_set;

        public function __construct(
            public string $promoted,
            string $non_promoted,
            string $non_promoted_with_default = 'default',
            public string $promoted_with_with_default = 'default',
        ) {
            $this->member = "changed_in_constructor: {$non_promoted}";
            $this->other_member = "changed_in_constructor: {$non_promoted_with_default}";
        }
    };

    $data = $dataClass::from([
        'promoted' => 'A',
        'non_promoted' => 'B',
        'non_promoted_with_default' => 'C',
        'promoted_with_with_default' => 'D',
        'member_to_set' => 'E',
        'member_with_default' => 'F',
    ]);

    expect($data->toArray())->toMatchArray([
        'member' => 'changed_in_constructor: B',
        'other_member' => 'changed_in_constructor: C',
        'member_with_default' => 'F',
        'promoted' => 'A',
        'promoted_with_with_default' => 'D',
        'member_to_set' => 'E',
    ]);

    $data = $dataClass::from([
        'promoted' => 'A',
        'non_promoted' => 'B',
        'member_to_set' => 'E',
    ]);

    expect($data->toArray())->toMatchArray([
        'member' => 'changed_in_constructor: B',
        'other_member' => 'changed_in_constructor: default',
        'member_with_default' => 'default',
        'promoted' => 'A',
        'promoted_with_with_default' => 'default',
        'member_to_set' => 'E',
    ]);
});

it('can cast data object and collectables using a custom cast', function () {
    $dataWithDefaultCastsClass = new class () extends Data {
        public SimpleData $nestedData;

        #[DataCollectionOf(SimpleData::class)]
        public array $nestedDataCollection;
    };

    $dataWithCustomCastsClass = new class () extends Data {
        #[WithCast(ConfidentialDataCast::class)]
        public SimpleData $nestedData;

        #[WithCast(ConfidentialDataCollectionCast::class)]
        #[DataCollectionOf(SimpleData::class)]
        public array $nestedDataCollection;
    };

    $dataWithDefaultCasts = $dataWithDefaultCastsClass::from([
        'nestedData' => 'a secret',
        'nestedDataCollection' => ['another secret', 'yet another secret'],
    ]);

    $dataWithCustomCasts = $dataWithCustomCastsClass::from([
        'nestedData' => 'a secret',
        'nestedDataCollection' => ['another secret', 'yet another secret'],
    ]);

    expect($dataWithDefaultCasts)
        ->nestedData->toEqual(SimpleData::from('a secret'))
        ->and($dataWithDefaultCasts)
        ->nestedDataCollection->toEqual(SimpleData::collect(['another secret', 'yet another secret']));

    expect($dataWithCustomCasts)
        ->nestedData->toEqual(SimpleData::from('CONFIDENTIAL'))
        ->and($dataWithCustomCasts)
        ->nestedDataCollection->toEqual(SimpleData::collect(['CONFIDENTIAL', 'CONFIDENTIAL']));
});

it('can create a data object with defaults by calling an empty from', function () {
    $dataClass = new class ('', '', '') extends Data {
        public function __construct(
            public ?string $string,
            public Optional|string $optionalString,
            public string $stringWithDefault = 'Hi',
        ) {
        }
    };

    expect(new $dataClass(null, new Optional(), 'Hi'))
        ->toEqual($dataClass::from([]));
});

it('can cast built-in types with custom casts', function () {
    $dataClass = new class ('', '') extends Data {
        public function __construct(
            public string $without_cast,
            #[WithCast(StringToUpperCast::class)]
            public string $with_cast
        ) {
        }
    };

    $data = $dataClass::from([
        'without_cast' => 'Hello World',
        'with_cast' => 'Hello World',
    ]);

    expect($data)
        ->without_cast->toEqual('Hello World')
        ->with_cast->toEqual('HELLO WORLD');
});

it('can cast data object with a castable property using anonymous class', function () {
    $dataWithCastablePropertyClass = new class (new SimpleCastable('')) extends Data {
        public function __construct(
            #[WithCastable(SimpleCastable::class)]
            public SimpleCastable $castableData,
        ) {
        }
    };

    $dataWithCastableProperty = $dataWithCastablePropertyClass::from(['castableData' => 'HELLO WORLD']);

    expect($dataWithCastableProperty)
        ->castableData->toEqual(new SimpleCastable('HELLO WORLD'));
});

it('can assign a false value and the process will continue', function () {
    $dataClass = new class () extends Data {
        public bool $false;

        public bool $true;
    };

    $data = $dataClass::from([
        'false' => false,
        'true' => true,
    ]);

    expect($data)
        ->false->toBeFalse()
        ->true->toBeTrue();
});

it('can create an partial data object using optional values', function () {
    $dataClass = new class ('', Optional::create(), Optional::create()) extends Data {
        public function __construct(
            public string $string,
            public string|Optional $undefinable_string,
            #[WithCast(StringToUpperCast::class)]
            public string|Optional $undefinable_string_with_cast,
        ) {
        }
    };

    $partialData = $dataClass::from([
        'string' => 'Hello World',
    ]);

    expect($partialData)
        ->string->toEqual('Hello World')
        ->undefinable_string->toEqual(Optional::create())
        ->undefinable_string_with_cast->toEqual(Optional::create());

    $fullData = $dataClass::from([
        'string' => 'Hello World',
        'undefinable_string' => 'Hello World',
        'undefinable_string_with_cast' => 'Hello World',
    ]);

    expect($fullData)
        ->string->toEqual('Hello World')
        ->undefinable_string->toEqual('Hello World')
        ->undefinable_string_with_cast->toEqual('HELLO WORLD');
});

it('can use context in casts based upon the properties of the data object', function () {
    $dataClass = new class () extends Data {
        public SimpleData $nested;

        public string $string;

        #[WithCast(ContextAwareCast::class)]
        public string $casted;
    };

    $data = $dataClass::from([
        'nested' => 'Hello',
        'string' => 'world',
        'casted' => 'json:',
    ]);

    expect($data)->casted
        ->toEqual('json:+{"nested":{"string":"Hello"},"string":"world","casted":"json:"}');
});

it(
    'will throw a custom exception when a data constructor cannot be called due to missing component',
    function () {
        SimpleData::from([]);
    }
)->throws(CannotCreateData::class, 'the constructor requires 1 parameters');

it('will take properties from a base class into account when creating a data object', function () {
    $dataClass = new class ('') extends SimpleData {
        public int $int;
    };

    $data = $dataClass::from(['string' => 'Hi', 'int' => 42]);

    expect($data)
        ->string->toBe('Hi')
        ->int->toBe(42);
});

it('can set a default value for data object which is taken into account when creating the data object', function () {
    $dataObject = new class ('', '') extends Data {
        #[Min(10)]
        public string|Optional $full_name;

        public function __construct(
            public string $first_name,
            public string $last_name,
        ) {
            $this->full_name = "{$this->first_name} {$this->last_name}";
        }
    };

    expect($dataObject::from(['first_name' => 'Ruben', 'last_name' => 'Van Assche']))
        ->first_name->toBe('Ruben')
        ->last_name->toBe('Van Assche')
        ->full_name->toBe('Ruben Van Assche');

    expect($dataObject::from(['first_name' => 'Ruben', 'last_name' => 'Van Assche', 'full_name' => 'Ruben Versieck']))
        ->first_name->toBe('Ruben')
        ->last_name->toBe('Van Assche')
        ->full_name->toBe('Ruben Versieck');

    expect($dataObject::validateAndCreate(['first_name' => 'Ruben', 'last_name' => 'Van Assche']))
        ->first_name->toBe('Ruben')
        ->last_name->toBe('Van Assche')
        ->full_name->toBe('Ruben Van Assche');

    expect(fn () => $dataObject::validateAndCreate(['first_name' => 'Ruben', 'last_name' => 'Van Assche', 'full_name' => 'too short']))
        ->toThrow(ValidationException::class);
});

it('can have a computed value when creating the data object', function () {
    $dataObject = new class ('', '') extends Data {
        #[Computed]
        public string $full_name;

        public function __construct(
            public string $first_name,
            public string $last_name,
        ) {
            $this->full_name = "{$this->first_name} {$this->last_name}";
        }
    };

    expect($dataObject::from(['first_name' => 'Ruben', 'last_name' => 'Van Assche', 'full_name' => 'Something to Be Ignored']))
        ->first_name->toBe('Ruben')
        ->last_name->toBe('Van Assche')
        ->full_name->toBe('Ruben Van Assche');

    expect($dataObject::validateAndCreate(['first_name' => 'Ruben', 'last_name' => 'Van Assche', 'full_name' => 'Something to Be Ignored']))
        ->first_name->toBe('Ruben')
        ->last_name->toBe('Van Assche')
        ->full_name->toBe('Ruben Van Assche');

    config()->set('data.features.ignore_exception_when_trying_to_set_computed_property_value', false);

    expect($dataObject::from(['first_name' => 'Ruben', 'last_name' => 'Van Assche']))
        ->first_name->toBe('Ruben')
        ->last_name->toBe('Van Assche')
        ->full_name->toBe('Ruben Van Assche');

    expect($dataObject::validateAndCreate(['first_name' => 'Ruben', 'last_name' => 'Van Assche']))
        ->first_name->toBe('Ruben')
        ->last_name->toBe('Van Assche')
        ->full_name->toBe('Ruben Van Assche');

    expect(fn () => $dataObject::from(['first_name' => 'Ruben', 'last_name' => 'Van Assche', 'full_name' => 'Ruben Versieck']))
        ->toThrow(CannotSetComputedValue::class);
});

it('can have a nullable computed value', function () {
    $dataObject = new class ('', '') extends Data {
        #[Computed]
        public ?string $upper_name;

        public function __construct(
            public ?string $name,
        ) {
            $this->upper_name = $name ? strtoupper($name) : null;
        }
    };

    expect($dataObject::from(['name' => 'Ruben']))
        ->name->toBe('Ruben')
        ->upper_name->toBe('RUBEN');

    expect($dataObject::from(['name' => null]))
        ->name->toBeNull()
        ->upper_name->toBeNull();

    expect($dataObject::validateAndCreate(['name' => 'Ruben']))
        ->name->toBe('Ruben')
        ->upper_name->toBe('RUBEN');

    expect($dataObject::validateAndCreate(['name' => null]))
        ->name->toBeNull()
        ->upper_name->toBeNull();

    config()->set('data.features.ignore_exception_when_trying_to_set_computed_property_value', false);

    expect(fn () => $dataObject::from(['name' => 'Ruben', 'upper_name' => 'RUBEN']))
        ->toThrow(CannotSetComputedValue::class);

    expect(fn () => $dataObject::from(['name' => 'Ruben', 'upper_name' => null]))
        ->name->toBeNull()
        ->upper_name->toBeNull(); // Case conflicts with DefaultsPipe, ignoring it for now
});

it('throws a readable exception message when the constructor fails', function (
    array $data,
    string $message,
) {
    try {
        MultiData::from($data);
    } catch (CannotCreateData $e) {
        expect($e->getMessage())->toBe($message);

        return;
    }

    throw new Exception('We should not reach this point');
})->with(fn () => [
    yield 'no params' => [[], 'Could not create `Uretral\BitrixData\Tests\Fakes\MultiData`: the constructor requires 2 parameters, 0 given. Parameters missing: first, second.'],
    yield 'one param' => [['first' => 'First'], 'Could not create `Uretral\BitrixData\Tests\Fakes\MultiData`: the constructor requires 2 parameters, 1 given. Parameters given: first. Parameters missing: second.'],
]);

it('throws a readable exception message when the constructor of a nested data object fails', function () {
    expect(fn () => NestedData::from([
        'simple' => [],
    ]))->toThrow(CannotCreateData::class, 'Could not create `Uretral\BitrixData\Tests\Fakes\SimpleData`: the constructor requires 1 parameters, 0 given. Parameters missing: string.');
});

it('a can create a collection of data objects', function () {
    $collectionA = new DataCollection(SimpleData::class, [
        SimpleData::from('A'),
        SimpleData::from('B'),
    ]);

    $collectionB = SimpleData::collect([
        'A',
        'B',
    ], DataCollection::class);

    expect($collectionB)->toArray()
        ->toMatchArray($collectionA->toArray());
});

it('can return a custom data collection when collecting data', function () {
    $class = new class ('') extends Data implements DeprecatedDataContract {
        use WithDeprecatedCollectionMethod;

        protected static string $_collectionClass = CustomDataCollection::class;

        public function __construct(public string $string)
        {
        }
    };

    $collection = $class::collection([
        ['string' => 'A'],
        ['string' => 'B'],
    ]);

    expect($collection)->toBeInstanceOf(CustomDataCollection::class);
});

it('can return a custom paginated data collection when collecting data', function () {
    $class = new class ('') extends Data implements DeprecatedDataContract {
        use WithDeprecatedCollectionMethod;

        protected static string $_paginatedCollectionClass = CustomPaginatedDataCollection::class;

        public function __construct(public string $string)
        {
        }
    };

    $collection = $class::collection(new LengthAwarePaginator([['string' => 'A'], ['string' => 'B']], 2, 15));

    expect($collection)->toBeInstanceOf(CustomPaginatedDataCollection::class);
});

it('can return a custom cursor paginated data collection when collecting data', function () {
    $class = new class ('') extends Data implements DeprecatedDataContract {
        use WithDeprecatedCollectionMethod;

        protected static string $_cursorPaginatedCollectionClass = CustomCursorPaginatedDataCollection::class;

        public function __construct(public string $string)
        {
        }
    };

    $collection = $class::collection(new CursorPaginator([['string' => 'A'], ['string' => 'B']], 2));

    expect($collection)->toBeInstanceOf(CustomCursorPaginatedDataCollection::class);
});

it('will allow a nested data object to cast properties however it wants', function () {
    $model = new DummyModel(['id' => 10]);

    $withoutModelData = NestedModelData::from([
        'model' => ['id' => 10],
    ]);

    expect($withoutModelData)
        ->toBeInstanceOf(NestedModelData::class)
        ->model->id->toEqual(10);

    /** @var \Uretral\BitrixData\Tests\Fakes\NestedModelData $data */
    $withModelData = NestedModelData::from([
        'model' => $model,
    ]);

    expect($withModelData)
        ->toBeInstanceOf(NestedModelData::class)
        ->model->id->toEqual(10);
});

it('will allow a nested collection object to cast properties however it wants', function () {
    $data = NestedModelCollectionData::from([
        'models' => [['id' => 10], ['id' => 20],],
    ]);

    expect($data)
        ->toBeInstanceOf(NestedModelCollectionData::class)
        ->models->toEqual(
            ModelData::collect([['id' => 10], ['id' => 20]], DataCollection::class)
        );

    $data = NestedModelCollectionData::from([
        'models' => [new DummyModel(['id' => 10]), new DummyModel(['id' => 20]),],
    ]);

    expect($data)
        ->toBeInstanceOf(NestedModelCollectionData::class)
        ->models->toEqual(
            ModelData::collect([['id' => 10], ['id' => 20]], DataCollection::class)
        );

    $data = NestedModelCollectionData::from([
        'models' => ModelData::collect([['id' => 10], ['id' => 20]], DataCollection::class),
    ]);

    expect($data)
        ->toBeInstanceOf(NestedModelCollectionData::class)
        ->models->toEqual(
            ModelData::collect([['id' => 10], ['id' => 20]], DataCollection::class)
        );
});

it('will ignore null or optional values, which are set by default in multiple payloads', function () {
    $dataClass = new class () extends Data {
        public string $string;

        public ?string $nullable;

        public Optional|string $optional;
    };

    $data = $dataClass::from(
        ['string' => 'string'],
        ['nullable' => 'nullable'],
        ['optional' => 'optional']
    );

    expect($data)
        ->string->toEqual('string')
        ->nullable->toEqual('nullable')
        ->optional->toEqual('optional');

    $data = $dataClass::from(
        ['optional' => 'optional'],
        ['string' => 'string'],
        ['nullable' => 'nullable'],
    );

    expect($data)
        ->string->toEqual('string')
        ->nullable->toEqual('nullable')
        ->optional->toEqual('optional');

    $data = $dataClass::from(
        ['nullable' => 'nullable'],
        ['optional' => 'optional'],
        ['string' => 'string'],
    );

    expect($data)
        ->string->toEqual('string')
        ->nullable->toEqual('nullable')
        ->optional->toEqual('optional');
});

it('a cast can return an empty array', function () {
    $dataclass = new class () extends Data {
        #[WithCast(ValueDefinedCast::class, value: [])]
        public array $items;
    };

    $data = $dataclass::from([
        'items' => ['does not matter'],
    ]);

    expect($data->items)->toEqual([]);
});

it('a cast can return null', function () {
    $dataclass = new class () extends Data {
        #[WithCast(ValueDefinedCast::class, value: null)]
        public ?array $items;
    };

    $data = $dataclass::from([
        'items' => ['does not matter'],
    ]);

    expect($data->items)->toBeNull();
});

it('can loop through multiple casts until the good one is found', function () {
    $dataclass = new class () extends Data {
        public Collection $items;
    };

    $data = $dataclass::factory()
        ->withCast(Collection::class, new ValueDefinedCast(Uncastable::create()))
        ->withCast(Enumerable::class, new ValueDefinedCast(collect(['Well, hello this cast is used!'])))
        ->from(['items' => ['not used']]);

    expect($data->items)->toEqual(collect(['Well, hello this cast is used!']));
});

it('can inject a data object in a controller', function () {
    class TestControllerDataInjection
    {
        public function __invoke(SimpleData $data)
        {
            return response('ok');
        }
    }

    Route::post('test', TestControllerDataInjection::class);

    postJson(action(TestControllerDataInjection::class), ['string' => 'Hello World'])->assertOk();
    postJson(action(TestControllerDataInjection::class), ['string' => 'Hello World'])->assertOk(); // caused an infinite loop once
});

it('can collect null when an output type is defined', function () {
    expect(SimpleData::collect(null, 'array'))
        ->toBeArray()
        ->toBeEmpty();
});

it('will cast array items when an iterable type is defined that can be cast', function () {
    $dataClass = new class () extends Data {
        /** @var array<string> */
        public array $array;

        /** @var Collection<int, string> */
        public Collection $collection;
    };

    /** @var Data $data */
    $data = $dataClass::factory()
        ->withCast('string', StringToUpperCast::class)
        ->from([
            'array' => ['hello', 'world'],
            'collection' => ['this', 'is', 'great'],
        ]);

    expect($data->array)->toEqual(['HELLO', 'WORLD']);
    expect($data->collection)->toEqual(collect(['THIS', 'IS', 'GREAT']));
})->skip(fn () => false === false);

it('will cast array items when an iterable type is defined and prefer it over a global cast', function () {
    config()->set('data.casts', [
        'string' => MeaningOfLifeCast::class,
    ]);

    $dataClass = new class () extends Data {
        /** @var array<string> */
        public array $array;

        /** @var Collection<int, string> */
        public Collection $collection;
    };

    /** @var Data $data */
    $data = $dataClass::factory()
        ->withCast('string', StringToUpperCast::class)
        ->from([
            'array' => ['hello', 'world'],
            'collection' => ['this', 'is', 'great'],
        ]);

    expect($data->array)->toEqual(['HELLO', 'WORLD']);
    expect($data->collection)->toEqual(collect(['THIS', 'IS', 'GREAT']));
})->skip(fn () => false === false);

it('will cast array items when an iterable interface type is defined that can be cast', function () {
    $dataClass = new class () extends Data {
        /** @var array<DateTime> */
        public array $dates;

        /** @var array<Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum> */
        public array $enums;
    };

    /** @var Data $data */
    $data = $dataClass::factory()
        ->withCast('string', StringToUpperCast::class)
        ->from([
            'dates' => [
                '2022-01-18T12:00:00Z',
                '2022-01-19T12:00:00Z',
            ],
            'enums' => [
                'boo',
                'foo',
            ],
        ]);

    expect($data->dates)->toEqual([
        DateTime::createFromFormat(DATE_ATOM, '2022-01-18T12:00:00Z'),
        DateTime::createFromFormat(DATE_ATOM, '2022-01-19T12:00:00Z'),
    ]);

    expect($data->enums)->toEqual([
        DummyBackedEnum::BOO,
        DummyBackedEnum::FOO,
    ]);
})->skip(fn () => false === false);

it('will cast iterables into the correct type', function () {
    $dataClass = new class () extends Data {
        public EloquentCollection $collection;

        public CustomCollection $customCollection;

        public array $array;
    };

    $data = $dataClass::from([
        'collection' => ['no', 'models', 'here'],
        'customCollection' => ['this', 'is', 'great'],
        'array' => collect(['a', 'collection']),
    ]);

    expect($data->collection)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toEqual(new EloquentCollection(['no', 'models', 'here']));

    expect($data->customCollection)
        ->toBeInstanceOf(CustomCollection::class)
        ->toEqual(new CustomCollection(['this', 'is', 'great']));

    expect($data->array)
        ->toBeArray()
        ->toEqual(['a', 'collection']);
})->skip(fn () => false === false);

it('will cast an empty iterables list into the correct type', function () {
    $dataClass = new class () extends Data {
        public EloquentCollection $collection;

        public CustomCollection $customCollection;

        public array $array;
    };

    $data = $dataClass::from([
        'collection' => [],
        'customCollection' => [],
        'array' => collect([]),
    ]);

    expect($data->collection)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toEqual(new EloquentCollection([]));

    expect($data->customCollection)
        ->toBeInstanceOf(CustomCollection::class)
        ->toEqual(new CustomCollection([]));

    expect($data->array)
        ->toBeArray()
        ->toEqual([]);
})->skip(fn () => false === false);

it('will cast iterables into default types', function () {
    $dataClass = new class () extends Data {
        /** @var array<int, string> */
        public array $strings;

        /** @var array<int, bool> */
        public array $bools;

        /** @var array<int, int> */
        public array $ints;

        /** @var array<int, float> */
        public array $floats;

        /** @var array<int, array> */
        public array $arrays;
    };

    $data = $dataClass::from([
        'strings' => ['Hello', 42, 3.14, true, '0', 'false'],
        'bools' => ['Hello', 42, 3.14, true, ['nested'], '0', 'false'],
        'ints' => ['Hello', 42, 3.14, true, ['nested'], '0', 'false'],
        'floats' => ['Hello', 42, 3.14, true, ['nested'], '0', 'false'],
        'arrays' => ['Hello', 42, 3.14, true, ['nested'], '0', 'false'],
    ]);

    expect($data->strings)->toBe(['Hello', '42', '3.14', '1', '0', 'false']);
    expect($data->bools)->toBe([true, true, true, true, true, false, true]);
    expect($data->ints)->toBe([0, 42, 3, 1, 1, 0, 0]);
    expect($data->floats)->toBe([0.0, 42.0, 3.14, 1.0, 1.0, 0.0, 0.0]);
    expect($data->arrays)->toEqual([['Hello'], [42], [3.14], [true], ['nested'], ['0'], ['false']]);
})->skip(fn () => false === false);

it('keeps the creation context path up to date', function () {
    class TestCreationContextCollectorDataPipe implements DataPipe
    {
        public static array $contexts = [];

        public function handle(mixed $payload, DataClass $class, array $properties, CreationContext $creationContext): array
        {
            static::$contexts[] = clone $creationContext;

            return $properties;
        }
    }

    class TestDataWithCreationContextCollectorPipe extends SimpleData
    {
        public static function pipeline(): DataPipeline
        {
            return parent::pipeline()->through(TestCreationContextCollectorDataPipe::class);
        }
    }

    $dataClass = new class () extends Data {
        #[DataCollectionOf(TestDataWithCreationContextCollectorPipe::class)]
        public Collection $collection;

        public static function pipeline(): DataPipeline
        {
            return parent::pipeline()->through(TestCreationContextCollectorDataPipe::class);
        }
    };

    $dataClass::from([
        'collection' => [['string' => 'no'], 'models', ['string' => 'here']],
    ]);

    expect(TestCreationContextCollectorDataPipe::$contexts)
        ->toHaveCount(3)
        ->each()->toBeInstanceOf(CreationContext::class);

    expect(TestCreationContextCollectorDataPipe::$contexts[0]->currentPath)->toBe([0 => 'collection', 1 => 0]);
    expect(TestCreationContextCollectorDataPipe::$contexts[1]->currentPath)->toBe([0 => 'collection', 1 => 2]);
    expect(TestCreationContextCollectorDataPipe::$contexts[2]->currentPath)->toHaveCount(0);

});

it('is possible to create an union type data object', function () {
    $dataClass = new class () extends Data {
        public string|SimpleData $property;
    };

    expect($dataClass::from(['property' => 'Hello World'])->property)->toBeInstanceOf(SimpleData::class);

    $dataClass = new class () extends Data {
        public int|SimpleData $property;
    };

    expect($dataClass::from(['property' => 10])->property)->toBeInt();
    expect($dataClass::from(['property' => 'Hello World'])->property)->toBeInstanceOf(SimpleData::class);

    $dataClass = new class () extends Data {
        public int|SimpleData|Optional|Lazy $property;
    };

    expect($dataClass::from(['property' => 10])->property)->toBeInt();
    expect($dataClass::from(['property' => 'Hello World'])->property)->toBeInstanceOf(SimpleData::class);
    expect($dataClass::from(['property' => Lazy::create(fn () => 10)])->property)->toBeInstanceOf(Lazy::class);
    expect($dataClass::from([])->property)->toBeInstanceOf(Optional::class);
});

it('is possible to create a union type data collectable', function () {
    $dataClass = new class () extends Data {
        /** @var array<int|SimpleData> */
        public array $property;
    };

    expect($dataClass::from(['property' => [10, 'Hello World']])->property)->toEqual(
        [10, SimpleData::from('Hello World')]
    );
})->todo();
