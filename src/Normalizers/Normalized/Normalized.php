<?php

namespace Uretral\BitrixData\Normalizers\Normalized;

use Uretral\BitrixData\Support\DataProperty;

interface Normalized
{
    public function getProperty(string $name, DataProperty $dataProperty): mixed;
}
