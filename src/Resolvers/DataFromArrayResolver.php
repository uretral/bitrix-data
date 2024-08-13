<?php

namespace Uretral\BitrixData\Resolvers;

use ArgumentCountError;
use Uretral\BitrixData\Contracts\BaseData;
use Uretral\BitrixData\Exceptions\CannotCreateData;
use Uretral\BitrixData\Exceptions\CannotSetComputedValue;
use Uretral\BitrixData\Optional;
use Uretral\BitrixData\Support\DataClass;
use Uretral\BitrixData\Support\DataConfig;

/**
 * @template TData of BaseData
 */
class DataFromArrayResolver
{
    public function __construct(protected DataConfig $dataConfig)
    {
    }

    /**
     * @param class-string<TData> $class
     *
     * @return TData
     */
    public function execute(string $class, array $properties): BaseData
    {
        $dataClass = $this->dataConfig->getDataClass($class);

        $data = $this->createData($dataClass, $properties);

        foreach ($dataClass->properties as $property) {
            if(
                $property->isPromoted
                || $property->isReadonly
                || ! array_key_exists($property->name, $properties)
            ) {
                continue;
            }

            if ($property->type->isOptional
                && isset($data->{$property->name})
                && $properties[$property->name] instanceof Optional
            ) {
                continue;
            }

            if ($property->computed
                && $property->type->isNullable
                && $properties[$property->name] === null
            ) {
                continue; // Nullable properties get assigned null by default
            }

            if ($property->computed) {
                if (! config('data.features.ignore_exception_when_trying_to_set_computed_property_value')) {
                    throw CannotSetComputedValue::create($property);
                }

                continue; // Ignore the value being passed into the computed property and let it be recalculated
            }

            $data->{$property->name} = $properties[$property->name];
        }

        return $data;
    }

    protected function createData(
        DataClass $dataClass,
        array $properties,
    ) {
        $constructorParameters = $dataClass->constructorMethod?->parameters;

        if ($constructorParameters === null) {
            return new $dataClass->name();
        }

        $parameters = [];

        foreach ($constructorParameters as $parameter) {
            if (array_key_exists($parameter->name, $properties)) {
                $parameters[$parameter->name] = $properties[$parameter->name];

                continue;
            }

            if (! $parameter->isPromoted && $parameter->hasDefaultValue) {
                $parameters[$parameter->name] = $parameter->defaultValue;
            }
        }

        try {
            return new $dataClass->name(...$parameters);
        } catch (ArgumentCountError $error) {
            throw CannotCreateData::constructorMissingParameters(
                $dataClass,
                $parameters,
                $error
            );
        }
    }
}
