<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Mockery\MockInterface;
use Uretral\BitrixData\Support\Caching\CachedDataConfig;
use Uretral\BitrixData\Support\Caching\DataStructureCache;
use Uretral\BitrixData\Support\DataClass;
use Uretral\BitrixData\Support\DataConfig;
use Uretral\BitrixData\Tests\Factories\FakeDataStructureFactory;
use Uretral\BitrixData\Tests\Fakes\SimpleData;

function ensureDataWillBeCached()
{
    App::forgetInstance(DataConfig::class);
    app()->singleton(
        DataConfig::class,
        function () {
            return app()->make(DataStructureCache::class)->getConfig() ?? DataConfig::createFromConfig(config('data'));
        }
    );
}

it('will use a cached data config if available', function () {
    ensureDataWillBeCached();

    $cache = app(DataStructureCache::class);

    $cachedDataConfig = new CachedDataConfig();

    $cache->storeConfig($cachedDataConfig);

    $config = app(DataConfig::class);

    expect($config)->toBeInstanceOf(CachedDataConfig::class);
});

it('will use a non cached config when a cached version is not available', function () {
    ensureDataWillBeCached();

    $config = app(DataConfig::class);

    expect($config)->toBeInstanceOf(DataConfig::class);
});

it('will use a cached data config if the cached version is invalid', function () {
    ensureDataWillBeCached();

    ['store' => $store, 'prefix' => $prefix] = config('data.structure_caching.cache');

    cache()->store($store)->forever("{$prefix}.config", serialize(new CachedDataConfig()));

    expect(app(DataConfig::class))->toBeInstanceOf(CachedDataConfig::class);

    cache()->store($store)->forever("{$prefix}.config", 'invalid');

    App::forgetInstance(DataConfig::class);

    expect(app(DataConfig::class))->toBeInstanceOf(DataConfig::class);
});

it('will load cached data classes', function () {
    ensureDataWillBeCached();

    $dataClass = FakeDataStructureFactory::class(SimpleData::class);
    $dataClass->prepareForCache();

    $mock = Mockery::mock(
        new DataStructureCache(config('data.structure_caching.cache')),
        function (MockInterface $spy) use ($dataClass) {
            $spy
                ->shouldReceive('getDataClass')
                ->with(SimpleData::class)
                ->andReturn($dataClass)
                ->once();
        }
    )->makePartial()->shouldAllowMockingProtectedMethods();

    $cachedDataConfig = (new CachedDataConfig())->setCache($mock);

    $mock->storeDataClass($dataClass);

    $cachedDataClass = $cachedDataConfig->getDataClass(SimpleData::class);

    expect($cachedDataClass)
        ->toBeInstanceOf(DataClass::class)
        ->name->toBe(SimpleData::class);
});

it('can disable caching', function () {
    config()->set('data.structure_caching.enabled', false);

    Cache::expects('get')->once();

    SimpleData::from('Hello world');

    cache()->get('something-just-to-test-the-mock');
});

it('will not cache when unit testing', function () {
    Cache::expects('get')->once();

    SimpleData::from('Hello world');

    cache()->get('something-just-to-test-the-mock');
});
