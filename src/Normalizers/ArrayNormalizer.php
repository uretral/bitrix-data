<?php

namespace Uretral\BitrixData\Normalizers;

class ArrayNormalizer implements Normalizer
{
    public function normalize(mixed $value): ?array
    {
        if (! is_array($value)) {
            return null;
        }

        return $value;
    }
}
