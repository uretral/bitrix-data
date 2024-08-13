<?php

namespace Uretral\BitrixData;

use Uretral\BitrixData\Concerns\BaseData;
use Uretral\BitrixData\Concerns\ValidateableData;
use Uretral\BitrixData\Contracts\BaseData as BaseDataContract;
use Uretral\BitrixData\Contracts\ValidateableData as ValidateableDataContract;

class Dto implements ValidateableDataContract, BaseDataContract
{
    use ValidateableData;
    use BaseData;
}
