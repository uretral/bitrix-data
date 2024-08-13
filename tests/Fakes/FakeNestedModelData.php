<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Carbon\CarbonImmutable;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\Lazy;
use Uretral\BitrixData\Optional;
use Uretral\BitrixData\Tests\Fakes\Models\FakeNestedModel;

class FakeNestedModelData extends Data
{
    public function __construct(
        public string $string,
        public ?string $nullable,
        public CarbonImmutable $date,
        public Optional|Lazy|FakeModelData|null $fake_model
    ) {
    }

    public static function createWithLazyWhenLoaded(FakeNestedModel $model)
    {
        return new self(
            $model->string,
            $model->nullable,
            $model->date,
            Lazy::whenLoaded('fakeModel', $model, fn () => FakeModelData::from($model->fakeModel)),
        );
    }
}
