<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;

class NestedModelData extends Data
{
    public function __construct(
        public ModelData $model
    ) {
    }
}
