<?php

use Illuminate\Support\Arr;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\DataPipeline;
use Uretral\BitrixData\DataPipes\AuthorizedDataPipe;
use Uretral\BitrixData\DataPipes\CastPropertiesDataPipe;
use Uretral\BitrixData\DataPipes\DefaultValuesDataPipe;

it('can prepend a data pipe at the beginning of the pipeline', function () {
    $pipeline = DataPipeline::create()
        ->through(DefaultValuesDataPipe::class)
        ->through(CastPropertiesDataPipe::class)
        ->firstThrough(AuthorizedDataPipe::class);

    $reflectionProperty = tap(
        new ReflectionProperty(DataPipeline::class, 'pipes'),
        static fn (ReflectionProperty $r) => $r->setAccessible(true),
    );

    $pipes = $reflectionProperty->getValue($pipeline);

    expect($pipes)
        ->toHaveCount(3)
        ->toMatchArray([
            AuthorizedDataPipe::class,
            DefaultValuesDataPipe::class,
            CastPropertiesDataPipe::class,
        ]);
});

it('can restructure payload before entering the pipeline', function () {
    $class = new class () extends Data {
        public function __construct(
            public string|null $name = null,
            public string|null $address = null,
        ) {
        }

        public static function prepareForPipeline(array $properties): array
        {
            $properties['address'] = implode(',', Arr::only($properties, ['line_1', 'city', 'state', 'zipcode']));

            return $properties;
        }
    };

    $instance = $class::from([
        'name' => 'Freek',
        'line_1' => '123 Sesame St',
        'city' => 'New York',
        'state' => 'NJ',
        'zipcode' => '10010',
    ]);

    expect($instance->toArray())->toMatchArray([
        'name' => 'Freek',
        'address' => '123 Sesame St,New York,NJ,10010',
    ]);
});
