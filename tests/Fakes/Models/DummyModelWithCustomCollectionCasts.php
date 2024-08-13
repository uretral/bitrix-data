<?php

namespace Uretral\BitrixData\Tests\Fakes\Models;

use Uretral\BitrixData\Tests\Fakes\SimpleData;
use Uretral\BitrixData\Tests\Fakes\SimpleDataCollection;

class DummyModelWithCustomCollectionCasts extends DummyModelWithCasts
{
    protected $table = 'dummy_model_with_casts';

    protected $casts = [
        'data' => SimpleData::class,
        'data_collection' => SimpleDataCollection::class.':'.SimpleData::class,
    ];
}
