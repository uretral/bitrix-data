<?php

namespace Uretral\BitrixData\Tests\Fakes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DummyModel extends Model
{
    protected $casts = [
        'date' => 'datetime',
        'nullable_date' => 'datetime',
        'optional_date' => 'datetime',
        'nullable_optional_date' => 'datetime',
        'boolean' => 'boolean',
    ];

    public static function migrate()
    {
        Schema::create('dummy_models', function (Blueprint $blueprint) {
            $blueprint->increments('id');

            $blueprint->string('string');
            $blueprint->dateTime('date');
            $blueprint->dateTime('nullable_date')->nullable();
            $blueprint->dateTime('optional_date')->nullable();
            $blueprint->dateTime('nullable_optional_date')->nullable();
            $blueprint->boolean('boolean');

            $blueprint->timestamps();
        });
    }
}
