<?php

/** @noinspection PhpExpressionResultUnusedInspection */

use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\Creation\CreationContextFactory;
use Uretral\BitrixData\Tests\Fakes\SimpleData;
use Uretral\BitrixData\Tests\Fakes\SimpleDto;
use Uretral\BitrixData\Tests\Fakes\SimpleResource;
use function PHPStan\Testing\assertType;

$factory = SimpleData::factory();
assertType(CreationContextFactory::class.'<'.SimpleData::class.'>', $factory);

// Data

$data = SimpleData::factory()->from('Hello World');
assertType(SimpleData::class, $data);

// Collection

$collection = SimpleData::factory()->collect(['A', 'B']);
assertType('array<int, '.SimpleData::class.'>', $collection);

$collection = SimpleData::factory()->collect(['A', 'B'], into: DataCollection::class);
assertType(DataCollection::class.'<int, '.SimpleData::class.'>', $collection);
