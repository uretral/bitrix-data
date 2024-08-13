<?php

namespace Uretral\BitrixData\Tests\Fakes\Models;

use Illuminate\Database\Eloquent\Model;
use Uretral\BitrixData\Tests\Fakes\SimpleData;
use Uretral\BitrixData\Tests\Fakes\SimpleDataCollection;
use Uretral\BitrixData\Tests\Fakes\SimpleDataWithDefaultValue;

class DummyModelWithDefaultCasts extends Model
{
    protected $casts = [
        'data' => SimpleDataWithDefaultValue::class.':default',
        'data_collection' => SimpleDataCollection::class.':'.SimpleData::class.',default',
    ];

    protected $table = 'dummy_model_with_casts';

    public $timestamps = false;
}
