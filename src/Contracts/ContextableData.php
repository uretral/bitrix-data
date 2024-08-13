<?php

namespace Uretral\BitrixData\Contracts;

use Uretral\BitrixData\Support\Transformation\DataContext;

interface ContextableData
{
    public function getDataContext(): DataContext;
}
