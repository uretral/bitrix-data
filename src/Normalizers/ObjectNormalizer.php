<?php

namespace Uretral\BitrixData\Normalizers;

use stdClass;

class ObjectNormalizer implements Normalizer
{
    public function normalize(mixed $value): ?array
    {
        if (! $value instanceof stdClass) {
            return null;
        }

        return (array) $value;
    }
}
