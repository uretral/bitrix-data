<?php

namespace Uretral\BitrixData\Tests\Fakes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\Tests\Fakes\AbstractData\AbstractData;
use Uretral\BitrixData\Tests\Fakes\SimpleData;

class DummyModelWithCasts extends Model
{
    protected $casts = [
        'data' => SimpleData::class,
        'data_collection' => DataCollection::class.':'.SimpleData::class,
        'abstract_data' => AbstractData::class,
        'abstract_collection' => DataCollection::class . ':' . AbstractData::class,
    ];

    public $timestamps = false;

    public static function migrate()
    {
        Schema::create('dummy_model_with_casts', function (Blueprint $blueprint) {
            $blueprint->increments('id');

            $blueprint->text('data')->nullable();
            $blueprint->text('data_collection')->nullable();
            $blueprint->text('abstract_data')->nullable();
            $blueprint->text('abstract_collection')->nullable();
        });
    }
}
