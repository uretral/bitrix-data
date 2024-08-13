<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Attributes\DataCollectionOf;
use Uretral\BitrixData\Attributes\MapName;
use Uretral\BitrixData\Data;
use Uretral\BitrixData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class DataWithMapper extends Data
{
    public string $casedProperty;

    public SimpleData $dataCasedProperty;

    #[DataCollectionOf(SimpleData::class)]
    public array $dataCollectionCasedProperty;
}
