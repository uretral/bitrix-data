<?php

namespace Uretral\BitrixData\Tests\Fakes\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Uretral\BitrixData\Tests\Factories\FakeNestedModelFactory;

class FakeNestedModel extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'immutable_datetime',
    ];

    public function fakeModel(): BelongsTo
    {
        return $this->belongsTo(FakeModel::class);
    }

    protected static function newFactory()
    {
        return FakeNestedModelFactory::new();
    }
}
