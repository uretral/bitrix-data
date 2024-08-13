<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Carbon\CarbonImmutable;
use DateTime;
use Uretral\BitrixData\Attributes\DataCollectionOf;
use Uretral\BitrixData\Attributes\WithCast;
use Uretral\BitrixData\Casts\DateTimeInterfaceCast;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\Optional;

class ComplicatedData extends Data
{
    public function __construct(
        public $withoutType,
        public int $int,
        public bool $bool,
        public float $float,
        public string $string,
        public array $array,
        public ?int $nullable,
        public int|Optional $undefinable,
        public mixed $mixed,
        #[WithCast(DateTimeInterfaceCast::class, format: 'd-m-Y', type: CarbonImmutable::class)]
        public  $explicitCast,
        public DateTime $defaultCast,
        public ?SimpleData $nestedData,
        /** @var \Uretral\BitrixData\Tests\Fakes\SimpleData[] */
        public ?DataCollection $nestedCollection,
        #[DataCollectionOf(SimpleData::class)]
        public array $nestedArray,
    ) {
    }

    public function toUserDefinedToArray(): array
    {
        return [
            'withoutType' => $this->withoutType,
            'int' => $this->int,
            'bool' => $this->bool,
            'float' => $this->float,
            'string' => $this->string,
            'array' => $this->array,
            'nullable' => $this->nullable,
            'undefinable' => $this->undefinable,
            'mixed' => $this->mixed,
            'explicitCast' => $this->explicitCast,
            'defaultCast' => $this->defaultCast,
            'nestedData' => $this->nestedData?->toUserDefinedToArray(),
            'nestedCollection' => array_map(fn (NestedData $data) => $data->toUserDefinedToArray(), $this->nestedCollection?->toCollection()->all() ?? []),
            'nestedArray' => array_map(fn (NestedData $data) => $data->toUserDefinedToArray(), $this->nestedArray),
        ];
    }
}
