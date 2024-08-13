<?php

use Illuminate\Support\Collection;
use Uretral\BitrixData\Attributes\MapInputName;
use Uretral\BitrixData\Attributes\MapName;
use Uretral\BitrixData\Attributes\MapOutputName;
use Uretral\BitrixData\Mappers\CamelCaseMapper;
use Uretral\BitrixData\Mappers\ProvidedNameMapper;
use Uretral\BitrixData\Mappers\SnakeCaseMapper;
use Uretral\BitrixData\Resolvers\NameMappersResolver;

function getAttributes(object $class): Collection
{
    return collect((new ReflectionProperty($class, 'property'))->getAttributes())
        ->map(fn (ReflectionAttribute $attribute) => $attribute->newInstance());
}

beforeEach(function () {
    $this->resolver = new NameMappersResolver();
});

it('can get an input and output mapper', function () {
    $attributes = getAttributes(new class () {
        #[MapInputName('input'), MapOutputName('output')]
        public $property;
    });

    expect($this->resolver->execute($attributes))->toMatchArray([
        'inputNameMapper' => new ProvidedNameMapper('input'),
        'outputNameMapper' => new ProvidedNameMapper('output'),
    ]);
});

it('can have no mappers', function () {
    $attributes = getAttributes(new class () {
        public $property;
    });

    expect($this->resolver->execute($attributes))->toMatchArray([
        'inputNameMapper' => null,
        'outputNameMapper' => null,
    ]);
});

it('can have a single map attribute', function () {
    $attributes = getAttributes(new class () {
        #[MapName('input', 'output')]
        public $property;
    });

    expect($this->resolver->execute($attributes))->toMatchArray([
        'inputNameMapper' => new ProvidedNameMapper('input'),
        'outputNameMapper' => new ProvidedNameMapper('output'),
    ]);
});

it('can overwrite a general map attribute', function () {
    $attributes = getAttributes(new class () {
        #[MapName('input', 'output'), MapInputName('input_overwritten')]
        public $property;
    });

    expect($this->resolver->execute($attributes))->toMatchArray([
        'inputNameMapper' => new ProvidedNameMapper('input_overwritten'),
        'outputNameMapper' => new ProvidedNameMapper('output'),
    ]);
});

it('can map an int', function () {
    $attributes = getAttributes(new class () {
        #[MapName(0, 3)]
        public $property;
    });

    expect($this->resolver->execute($attributes))->toMatchArray([
        'inputNameMapper' => new ProvidedNameMapper(0),
        'outputNameMapper' => new ProvidedNameMapper(3),
    ]);
});

it('can map a string', function () {
    $attributes = getAttributes(new class () {
        #[MapName('hello', 'world')]
        public $property;
    });

    expect($this->resolver->execute($attributes))->toMatchArray([
        'inputNameMapper' => new ProvidedNameMapper('hello'),
        'outputNameMapper' => new ProvidedNameMapper('world'),
    ]);
});

it('can map a mapper class', function () {
    $attributes = getAttributes(new class () {
        #[MapName(CamelCaseMapper::class, SnakeCaseMapper::class)]
        public $property;
    });

    expect($this->resolver->execute($attributes))->toMatchArray([
        'inputNameMapper' => new CamelCaseMapper(),
        'outputNameMapper' => new SnakeCaseMapper(),
    ]);
});

it('can ignore certain mapper types', function () {
    $attributes = getAttributes(new class () {
        #[MapInputName('input'), MapOutputName(CamelCaseMapper::class)]
        public $property;
    });

    expect(NameMappersResolver::create([ProvidedNameMapper::class])
        ->execute($attributes))->toMatchArray([
        'inputNameMapper' => null,
        'outputNameMapper' => new CamelCaseMapper(),
    ]);
});
