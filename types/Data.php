<?php

/** @noinspection PhpExpressionResultUnusedInspection */

use Uretral\BitrixData\Support\Creation\CreationContextFactory;
use Uretral\BitrixData\Tests\Fakes\SimpleData;
use Uretral\BitrixData\Tests\Fakes\SimpleDto;
use Uretral\BitrixData\Tests\Fakes\SimpleResource;
use function PHPStan\Testing\assertType;

$data = SimpleData::from('Hello World');
assertType(SimpleData::class, $data);

$data = SimpleDto::from('Hello World');
assertType(SimpleDto::class, $data);

$data = SimpleResource::from('Hello World');
assertType(SimpleResource::class, $data);
