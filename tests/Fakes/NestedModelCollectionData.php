<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\DataCollection;

class NestedModelCollectionData extends Data
{
    public function __construct(
        /** @var \Uretral\BitrixData\Tests\Fakes\ModelData[] */
        public DataCollection $models
    ) {
    }
}
