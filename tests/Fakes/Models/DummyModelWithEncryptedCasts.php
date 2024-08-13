<?php

namespace Uretral\BitrixData\Tests\Fakes\Models;

use Illuminate\Database\Eloquent\Model;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\Tests\Fakes\AbstractData\AbstractData;
use Uretral\BitrixData\Tests\Fakes\SimpleData;
use Uretral\BitrixData\Tests\Fakes\SimpleDataCollection;

class DummyModelWithEncryptedCasts extends Model
{
    protected $casts = [
        'data' => SimpleData::class.':encrypted',
        'data_collection' => SimpleDataCollection::class.':'.SimpleData::class.',encrypted',
        'abstract_data' => AbstractData::class.':encrypted',
        'abstract_collection' => DataCollection::class . ':' . AbstractData::class.',encrypted',
    ];

    protected $table = 'dummy_model_with_casts';

    public $timestamps = false;
}
