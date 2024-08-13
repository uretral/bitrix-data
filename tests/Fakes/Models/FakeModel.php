<?php

namespace Uretral\BitrixData\Tests\Fakes\Models;

use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Uretral\BitrixData\Tests\Factories\FakeModelFactory;

class FakeModel extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'immutable_datetime',
    ];

    public function fakeNestedModels(): HasMany
    {
        return $this->hasMany(FakeNestedModel::class);
    }

    public function fake_nested_models_snake_cased(): HasMany
    {
        return $this->hasMany(FakeNestedModel::class);
    }

    public function accessor(): Attribute
    {
        return Attribute::get(fn () => "accessor_{$this->string}");
    }

    public function getOldAccessorAttribute()
    {
        return "old_accessor_{$this->string}";
    }

    public function getPerformanceHeavyAttribute()
    {
        throw new Exception('This attribute should not be called');
    }

    public function performanceHeavyAccessor(): Attribute
    {
        return Attribute::get(fn () => throw new Exception('This accessor should not be called'));
    }

    public function getAccessorUsingRelationAttribute(): string
    {
        return $this->fakeNestedModels->first()->string;
    }

    protected static function newFactory()
    {
        return FakeModelFactory::new();
    }
}
