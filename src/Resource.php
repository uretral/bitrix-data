<?php

namespace Uretral\BitrixData;

use Uretral\BitrixData\Concerns\AppendableData;
use Uretral\BitrixData\Concerns\BaseData;
use Uretral\BitrixData\Concerns\ContextableData;
use Uretral\BitrixData\Concerns\EmptyData;
use Uretral\BitrixData\Concerns\IncludeableData;
use Uretral\BitrixData\Concerns\ResponsableData;
use Uretral\BitrixData\Concerns\TransformableData;
use Uretral\BitrixData\Concerns\WrappableData;
use Uretral\BitrixData\Contracts\AppendableData as AppendableDataContract;
use Uretral\BitrixData\Contracts\BaseData as BaseDataContract;
use Uretral\BitrixData\Contracts\EmptyData as EmptyDataContract;
use Uretral\BitrixData\Contracts\IncludeableData as IncludeableDataContract;
use Uretral\BitrixData\Contracts\ResponsableData as ResponsableDataContract;
use Uretral\BitrixData\Contracts\TransformableData as TransformableDataContract;
use Uretral\BitrixData\Contracts\WrappableData as WrappableDataContract;
use Uretral\BitrixData\DataPipes\CastPropertiesDataPipe;
use Uretral\BitrixData\DataPipes\DefaultValuesDataPipe;
use Uretral\BitrixData\DataPipes\FillRouteParameterPropertiesDataPipe;
use Uretral\BitrixData\DataPipes\MapPropertiesDataPipe;

class Resource implements BaseDataContract, AppendableDataContract, IncludeableDataContract, TransformableDataContract, ResponsableDataContract, WrappableDataContract, EmptyDataContract
{
    use BaseData;
    use AppendableData;
    use IncludeableData;
    use ResponsableData;
    use TransformableData;
    use WrappableData;
    use EmptyData;
    use ContextableData;

    public static function pipeline(): DataPipeline
    {
        return DataPipeline::create()
            ->into(static::class)
            ->through(MapPropertiesDataPipe::class)
            ->through(FillRouteParameterPropertiesDataPipe::class)
            ->through(DefaultValuesDataPipe::class)
            ->through(CastPropertiesDataPipe::class);
    }
}
