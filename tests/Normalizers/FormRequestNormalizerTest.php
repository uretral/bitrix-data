<?php

use Illuminate\Foundation\Http\FormRequest;
use Uretral\BitrixData\Normalizers\ArrayNormalizer;
use Uretral\BitrixData\Normalizers\FormRequestNormalizer;
use Uretral\BitrixData\Tests\Fakes\DataWithNullable;

beforeEach(function () {
    config()->set('data.normalizers', [FormRequestNormalizer::class, ArrayNormalizer::class]);
});

it('will not normalize any other thing than a FormRequest', function () {
    $data = DataWithNullable::from([
        'string' => 'Hello',
        'nullableString' => 'World',
    ]);

    expect($data->toArray())->toEqual([
        'string' => 'Hello',
        'nullableString' => 'World',
    ]);
});

it('can create a data object from FormRequest', function () {
    $request = new class () extends FormRequest {
        public function rules(): array
        {
            return [
                'string' => 'required|string',
                'nullableString' => 'nullable|string',
            ];
        }
    };
    $request
        ->replace([
            'string' => 'Hello',
            'nullableString' => 'World',
        ])
        ->setContainer(app())
        ->validateResolved();

    $originalData = new DataWithNullable('Hello', 'World');
    $createdData = DataWithNullable::from($request);

    expect($createdData)->toEqual($originalData);
});

it("excludes unsafe data", function () {
    $request = new class () extends FormRequest {
        public function rules(): array
        {
            return [
                'string' => 'required|string',
            ];
        }
    };
    $request
        ->replace([
            'string' => 'Hello',
            'nullableString' => 'World',
        ])
        ->setContainer(app())
        ->validateResolved();

    $originalData = new DataWithNullable('Hello', null);
    $createdData = DataWithNullable::from($request);

    expect($createdData)->toEqual($originalData);
});
