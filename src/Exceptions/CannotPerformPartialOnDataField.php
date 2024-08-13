<?php

namespace Uretral\BitrixData\Exceptions;

use ErrorException;
use Exception;
use Uretral\BitrixData\Support\DataClass;
use Uretral\BitrixData\Support\Partials\PartialType;
use Uretral\BitrixData\Support\Transformation\TransformationContext;

class CannotPerformPartialOnDataField extends Exception
{
    public static function create(
        ErrorException $exception,
        PartialType $partialType,
        string $field,
        DataClass $dataClass,
        TransformationContext $transformationContext,
    ): self {
        $message = "Tried to {$partialType->getVerb()} a non existing field `{$field}` on `{$dataClass->name}`.".PHP_EOL;
        $message .= 'Provided transformation context:'.PHP_EOL.PHP_EOL;
        $message .= (string) $transformationContext;

        return new self(message: $message, previous: $exception);
    }
}
