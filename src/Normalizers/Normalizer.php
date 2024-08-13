<?php

namespace Uretral\BitrixData\Normalizers;

use Uretral\BitrixData\Normalizers\Normalized\Normalized;

interface Normalizer
{
    public function normalize(mixed $value): null|array|Normalized;
}
