<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Carbon\CarbonImmutable;
use Uretral\BitrixData\Attributes\DataCollectionOf;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\Optional;

class FakeModelData extends Data
{
    public function __construct(
        public string $string,
        public ?string $nullable,
        public CarbonImmutable $date,
        #[DataCollectionOf(FakeNestedModelData::class)]
        public Optional|null|DataCollection $fake_nested_models,
        public string $accessor,
        public string $old_accessor,
    ) {
    }
}
