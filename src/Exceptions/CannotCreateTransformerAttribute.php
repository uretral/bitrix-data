<?php

namespace Uretral\BitrixData\Exceptions;

use Exception;
use Uretral\BitrixData\Transformers\Transformer;

class CannotCreateTransformerAttribute extends Exception
{
    public static function notATransformer(): self
    {
        $transformer = Transformer::class;

        return new self("WithTransformer attribute needs a transformer that implements `{$transformer}`");
    }
}
