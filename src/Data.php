<?php

namespace Uretral\BitrixData;

use Illuminate\Contracts\Support\Responsable;
use Uretral\BitrixData\Concerns\AppendableData;
use Uretral\BitrixData\Concerns\BaseData;
use Uretral\BitrixData\Concerns\ContextableData;
use Uretral\BitrixData\Concerns\EmptyData;
use Uretral\BitrixData\Concerns\IncludeableData;
use Uretral\BitrixData\Concerns\ResponsableData;
use Uretral\BitrixData\Concerns\TransformableData;
use Uretral\BitrixData\Concerns\ValidateableData;
use Uretral\BitrixData\Concerns\WrappableData;
use Uretral\BitrixData\Contracts\AppendableData as AppendableDataContract;
use Uretral\BitrixData\Contracts\BaseData as BaseDataContract;
use Uretral\BitrixData\Contracts\EmptyData as EmptyDataContract;
use Uretral\BitrixData\Contracts\IncludeableData as IncludeableDataContract;
use Uretral\BitrixData\Contracts\ResponsableData as ResponsableDataContract;
use Uretral\BitrixData\Contracts\TransformableData as TransformableDataContract;
use Uretral\BitrixData\Contracts\ValidateableData as ValidateableDataContract;
use Uretral\BitrixData\Contracts\WrappableData as WrappableDataContract;

abstract class Data implements Responsable, AppendableDataContract, BaseDataContract, TransformableDataContract, IncludeableDataContract, ResponsableDataContract, ValidateableDataContract, WrappableDataContract, EmptyDataContract
{
    use ResponsableData;
    use IncludeableData;
    use AppendableData;
    use ValidateableData;
    use WrappableData;
    use TransformableData;
    use BaseData;
    use EmptyData;
    use ContextableData;
}
