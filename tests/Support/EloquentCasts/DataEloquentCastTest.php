<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\assertDatabaseHas;

use Uretral\BitrixData\Support\DataConfig;
use Uretral\BitrixData\Tests\Fakes\AbstractData\AbstractDataA;
use Uretral\BitrixData\Tests\Fakes\AbstractData\AbstractDataB;
use Uretral\BitrixData\Tests\Fakes\Models\DummyModelWithCasts;
use Uretral\BitrixData\Tests\Fakes\Models\DummyModelWithDefaultCasts;
use Uretral\BitrixData\Tests\Fakes\Models\DummyModelWithEncryptedCasts;
use Uretral\BitrixData\Tests\Fakes\SimpleData;
use Uretral\BitrixData\Tests\Fakes\SimpleDataWithDefaultValue;

beforeEach(function () {
    DummyModelWithCasts::migrate();
});

it('can save a data object', function () {
    DummyModelWithCasts::create([
        'data' => new SimpleData('Test'),
    ]);

    assertDatabaseHas(DummyModelWithCasts::class, [
        'data' => json_encode(['string' => 'Test']),
    ]);
});

it('can save a data object as an array', function () {
    DummyModelWithCasts::create([
        'data' => ['string' => 'Test'],
    ]);

    assertDatabaseHas(DummyModelWithCasts::class, [
        'data' => json_encode(['string' => 'Test']),
    ]);
});

it('can load a data object', function () {
    DB::table('dummy_model_with_casts')->insert([
        'data' => json_encode(['string' => 'Test']),
    ]);

    /** @var \Uretral\BitrixData\Tests\Fakes\Models\DummyModelWithCasts $model */
    $model = DummyModelWithCasts::first();

    expect($model->data)->toEqual(new SimpleData('Test'));
});

it('can save a null as a value', function () {
    DummyModelWithCasts::create([
        'data' => null,
    ]);

    assertDatabaseHas(DummyModelWithCasts::class, [
        'data' => null,
    ]);
});

it('can load null as a value', function () {
    DB::table('dummy_model_with_casts')->insert([
        'data' => null,
    ]);

    /** @var \Uretral\BitrixData\Tests\Fakes\Models\DummyModelWithCasts $model */
    $model = DummyModelWithCasts::first();

    expect($model->data)->toBeNull();
});

it('loads a cast object when nullable argument used and value is null in database', function () {
    DB::table('dummy_model_with_casts')->insert([
        'data' => null,
    ]);

    /** @var \Uretral\BitrixData\Tests\Fakes\Models\DummyModelWithDefaultCasts $model */
    $model = DummyModelWithDefaultCasts::first();

    expect($model->data)
        ->toBeInstanceOf(SimpleDataWithDefaultValue::class)
        ->string->toEqual('default');
});

it('can use an abstract data class with multiple children', function () {
    $abstractA = new AbstractDataA('A\A');
    $abstractB = new AbstractDataB('B\B');

    $modelId = DummyModelWithCasts::create([
        'abstract_data' => $abstractA,
    ])->id;

    $model = DummyModelWithCasts::find($modelId);

    expect($model->abstract_data)
        ->toBeInstanceOf(AbstractDataA::class)
        ->a->toBe('A\A');

    $model->abstract_data = $abstractB;
    $model->save();

    $model = DummyModelWithCasts::find($modelId);

    expect($model->abstract_data)
        ->toBeInstanceOf(AbstractDataB::class)
        ->b->toBe('B\B');
});

it('can use an abstract data class with morph map', function () {
    app(DataConfig::class)->enforceMorphMap([
        'a' => AbstractDataA::class,
    ]);

    $abstractA = new AbstractDataA('A\A');
    $abstractB = new AbstractDataB('B\B');

    $modelA = DummyModelWithCasts::create([
        'abstract_data' => $abstractA,
    ]);

    $modelB = DummyModelWithCasts::create([
        'abstract_data' => $abstractB,
    ]);

    expect(json_decode($modelA->getRawOriginal('abstract_data'))->type)->toBe('a');
    expect(json_decode($modelB->getRawOriginal('abstract_data'))->type)->toBe(AbstractDataB::class);

    $loadedMorphedModel = DummyModelWithCasts::find($modelA->id);

    expect($loadedMorphedModel->abstract_data)
        ->toBeInstanceOf(AbstractDataA::class)
        ->a->toBe('A\A');
});

it('can save an encrypted data object', function () {
    $model = DummyModelWithEncryptedCasts::create([
        'data' => new SimpleData('Test'),
    ]);

    try {
        Crypt::decryptString($model->getRawOriginal('data'));
        $isEncrypted = true;
    } catch (DecryptException $e) {
        $isEncrypted = false;
    }

    expect($isEncrypted)->toBeTrue();
});

it('can load an encrypted data object', function () {
    DummyModelWithEncryptedCasts::create([
        'data' => new SimpleData('Test'),
    ]);

    /** @var \Uretral\BitrixData\Tests\Fakes\Models\DummyModelWithCasts $model */
    $model = DummyModelWithEncryptedCasts::first();

    expect($model->data)->toEqual(new SimpleData('Test'));
});

it('can load and save an abstract defined data object', function () {
    $abstractA = new AbstractDataA('A\A');

    $modelId = DummyModelWithEncryptedCasts::create([
        'abstract_data' => $abstractA,
    ])->id;

    $model = DummyModelWithEncryptedCasts::find($modelId);

    expect($model->abstract_data)
        ->toBeInstanceOf(AbstractDataA::class)
        ->a->toBe('A\A');


    try {
        Crypt::decryptString($model->getRawOriginal('abstract_data'));
        $isEncrypted = true;
    } catch (DecryptException $e) {
        $isEncrypted = false;
    }

    expect($isEncrypted)->toBeTrue();
});
