<?php

namespace Uretral\BitrixData\Normalizers;

use Illuminate\Database\Eloquent\Model;
use Uretral\BitrixData\Normalizers\Normalized\Normalized;
use Uretral\BitrixData\Normalizers\Normalized\NormalizedModel;

class ModelNormalizer implements Normalizer
{
    public function normalize(mixed $value): null|array|Normalized
    {
        if (! $value instanceof Model) {
            return null;
        }

        return new NormalizedModel($value);
    }
}
