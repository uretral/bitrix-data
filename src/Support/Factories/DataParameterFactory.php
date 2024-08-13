<?php

namespace Uretral\BitrixData\Support\Factories;

use ReflectionClass;
use ReflectionParameter;
use Uretral\BitrixData\Support\DataParameter;

class DataParameterFactory
{
    public function __construct(
        protected DataTypeFactory $typeFactory,
    ) {
    }

    public function build(
        ReflectionParameter $reflectionParameter,
        ReflectionClass $reflectionClass,
    ): DataParameter {
        $hasDefaultValue = $reflectionParameter->isDefaultValueAvailable();

        return new DataParameter(
            $reflectionParameter->name,
            $reflectionParameter->isPromoted(),
            $hasDefaultValue,
            $hasDefaultValue ? $reflectionParameter->getDefaultValue() : null,
            $this->typeFactory->build(
                $reflectionParameter->getType(),
                $reflectionClass,
                $reflectionParameter,
            ),
        );
    }
}
