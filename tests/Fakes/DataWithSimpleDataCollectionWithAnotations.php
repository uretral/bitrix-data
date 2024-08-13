<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Tests\Fakes\Collections\SimpleDataCollectionWithAnotations;

class DataWithSimpleDataCollectionWithAnotations extends Data
{
    public function __construct(
        public SimpleDataCollectionWithAnotations $collection
    ) {
    }
}
