<?php

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Validation\ValidationException;
use Uretral\BitrixData\Concerns\AppendableData;
use Uretral\BitrixData\Concerns\BaseData;
use Uretral\BitrixData\Concerns\ContextableData;
use Uretral\BitrixData\Concerns\EmptyData;
use Uretral\BitrixData\Concerns\IncludeableData;
use Uretral\BitrixData\Concerns\ResponsableData;
use Uretral\BitrixData\Concerns\TransformableData;
use Uretral\BitrixData\Concerns\ValidateableData;
use Uretral\BitrixData\Concerns\WrappableData;
use Uretral\BitrixData\Contracts\AppendableData as AppendableDataContract;
use Uretral\BitrixData\Contracts\BaseData as BaseDataContract;
use Uretral\BitrixData\Contracts\EmptyData as EmptyDataContract;
use Uretral\BitrixData\Contracts\IncludeableData as IncludeableDataContract;
use Uretral\BitrixData\Contracts\ResponsableData as ResponsableDataContract;
use Uretral\BitrixData\Contracts\TransformableData as TransformableDataContract;
use Uretral\BitrixData\Contracts\ValidateableData as ValidateableDataContract;
use Uretral\BitrixData\Contracts\WrappableData as WrappableDataContract;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\Dto;
use Uretral\BitrixData\Resource;
use Uretral\BitrixData\Tests\Fakes\SimpleDto;
use Uretral\BitrixData\Tests\Fakes\SimpleResource;

it('also works by using traits and interfaces, skipping the base data class', function () {
    $data = new class ('') implements Responsable, AppendableDataContract, BaseDataContract, TransformableDataContract, IncludeableDataContract, ResponsableDataContract, ValidateableDataContract, WrappableDataContract, EmptyDataContract {
        use ResponsableData;
        use IncludeableData;
        use AppendableData;
        use ValidateableData;
        use WrappableData;
        use TransformableData;
        use BaseData;
        use EmptyData;
        use ContextableData;

        public function __construct(public string $string)
        {
        }

        public static function fromString(string $string): static
        {
            return new self($string);
        }
    };

    expect($data::from('Hi')->toArray())->toMatchArray(['string' => 'Hi'])
        ->and($data::from(['string' => 'Hi']))->toEqual(new $data('Hi'))
        ->and($data::from('Hi'))->toEqual(new $data('Hi'));
});


it('can use data as an DTO', function () {
    $dto = SimpleDto::from('Hello World');

    expect($dto)->toBeInstanceOf(SimpleDto::class)
        ->toBeInstanceOf(Dto::class)
        ->not()->toBeInstanceOf(Data::class)
        ->not()->toHaveMethods(['toArray', 'toJson', 'toResponse', 'all', 'include', 'exclude', 'only', 'except', 'transform', 'with', 'jsonSerialize'])
        ->and($dto->string)->toEqual('Hello World');

    expect(fn () => SimpleDto::validate(['string' => null]))->toThrow(ValidationException::class);
});

it('can use data as an Resource', function () {
    $resource = SimpleResource::from('Hello World');

    expect($resource)->toBeInstanceOf(SimpleResource::class)
        ->toBeInstanceOf(Resource::class)
        ->not()->toBeInstanceOf(Data::class)
        ->toHaveMethods(['toArray', 'toJson', 'toResponse', 'all', 'include', 'exclude', 'only', 'except', 'transform', 'with', 'jsonSerialize'])
        ->and($resource->string)->toEqual('Hello World');

    expect($resource)->not()->toHaveMethods([
        'validate',
    ]);
});
