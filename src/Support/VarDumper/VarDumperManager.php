<?php

namespace Uretral\BitrixData\Support\VarDumper;

use Uretral\BitrixData\Contracts\BaseData;
use Uretral\BitrixData\Contracts\BaseDataCollectable;
use Symfony\Component\VarDumper\Cloner\AbstractCloner;

class VarDumperManager
{
    public function initialize(): void
    {
        AbstractCloner::$defaultCasters[BaseData::class] = [DataVarDumperCaster::class, 'castDataObject'];
        AbstractCloner::$defaultCasters[BaseDataCollectable::class] = [DataVarDumperCaster::class, 'castDataCollectable'];
    }
}
