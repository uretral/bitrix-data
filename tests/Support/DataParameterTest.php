<?php

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\Creation\CreationContextFactory;
use Uretral\BitrixData\Support\DataType;
use Uretral\BitrixData\Tests\Factories\FakeDataStructureFactory;
use Uretral\BitrixData\Tests\Fakes\SimpleData;

it('can create a data parameter', function () {
    $class = new class ('', '', '', CreationContextFactory::createFromConfig(SimpleData::class)->get()) extends Data {
        public function __construct(
            string $nonPromoted,
            public $withoutType,
            public string $property,
            CreationContext $creationContext,
            public string $propertyWithDefault = 'hello',
        ) {
        }
    };

    $reflection = new ReflectionParameter([$class::class, '__construct'], 'nonPromoted');
    $parameter = FakeDataStructureFactory::parameter($reflection);

    expect($parameter)
        ->name->toEqual('nonPromoted')
        ->isPromoted->toBeFalse()
        ->hasDefaultValue->toBeFalse()
        ->defaultValue->toBeNull()
        ->type->toBeInstanceOf(DataType::class)
        ->type->type->name->toBe('string')
        ->type->isNullable->toBeFalse()
        ->type->type->isCreationContext()->toBeFalse();

    $reflection = new ReflectionParameter([$class::class, '__construct'], 'withoutType');
    $parameter = FakeDataStructureFactory::parameter($reflection);

    expect($parameter)
        ->name->toEqual('withoutType')
        ->isPromoted->toBeTrue()
        ->hasDefaultValue->toBeFalse()
        ->defaultValue->toBeNull()
        ->type->toBeInstanceOf(DataType::class)
        ->type->isMixed->toBeTrue()
        ->type->isNullable->toBeTrue()
        ->type->type->isCreationContext()->toBeFalse();

    $reflection = new ReflectionParameter([$class::class, '__construct'], 'property');
    $parameter = FakeDataStructureFactory::parameter($reflection);

    expect($parameter)
        ->name->toEqual('property')
        ->isPromoted->toBeTrue()
        ->hasDefaultValue->toBeFalse()
        ->defaultValue->toBeNull()
        ->type->toBeInstanceOf(DataType::class)
        ->type->type->name->toBe('string')
        ->type->isNullable->toBeFalse()
        ->type->type->isCreationContext()->toBeFalse();

    $reflection = new ReflectionParameter([$class::class, '__construct'], 'creationContext');
    $parameter = FakeDataStructureFactory::parameter($reflection);

    expect($parameter)
        ->name->toEqual('creationContext')
        ->isPromoted->toBeFalse()
        ->hasDefaultValue->toBeFalse()
        ->defaultValue->toBeNull()
        ->type->toBeInstanceOf(DataType::class)
        ->type->type->name->toBe(CreationContext::class)
        ->type->isNullable->toBeFalse()
        ->type->type->isCreationContext()->toBeTrue();

    $reflection = new ReflectionParameter([$class::class, '__construct'], 'propertyWithDefault');
    $parameter = FakeDataStructureFactory::parameter($reflection);

    expect($parameter)
        ->name->toEqual('propertyWithDefault')
        ->isPromoted->toBeTrue()
        ->hasDefaultValue->toBeTrue()
        ->defaultValue->toEqual('hello')
        ->type->toBeInstanceOf(DataType::class)
        ->type->type->name->toBe('string')
        ->type->isNullable->toBeFalse()
        ->type->type->isCreationContext()->toBeFalse();
});
