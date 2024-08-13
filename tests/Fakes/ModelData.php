<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Tests\Fakes\Models\DummyModel;

class ModelData extends Data
{
    public function __construct(
        public int $id
    ) {
    }

    public static function fromDummyModel(DummyModel $model)
    {
        return new self($model->id);
    }
}
