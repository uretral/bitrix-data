<?php

namespace Uretral\BitrixData\Resolvers;

use Uretral\BitrixData\Concerns\EmptyData;
use Uretral\BitrixData\Exceptions\DataPropertyCanOnlyHaveOneType;
use Uretral\BitrixData\Support\DataConfig;
use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Types\CombinationType;
use Traversable;

class EmptyDataResolver
{
    public function __construct(protected DataConfig $dataConfig)
    {
    }

    public function execute(string $class, array $extra = []): array
    {
        $dataClass = $this->dataConfig->getDataClass($class);

        $payload = [];

        foreach ($dataClass->properties as $property) {
            $name = $property->outputMappedName ?? $property->name;

            if ($property->hasDefaultValue) {
                $payload[$name] = $property->defaultValue;
            } else {
                $payload[$name] = $extra[$property->name] ?? $this->getValueForProperty($property);
            }
        }

        return $payload;
    }

    protected function getValueForProperty(DataProperty $property): mixed
    {
        $propertyType = $property->type;

        if ($propertyType->isMixed) {
            return null;
        }

        if ($propertyType->type instanceof CombinationType && count($propertyType->type->types) > 1) {
            throw DataPropertyCanOnlyHaveOneType::create($property);
        }

        if ($propertyType->type->acceptsType('array')) {
            return [];
        }

        if ($propertyType->kind->isDataObject()
            && $this->dataConfig->getDataClass($propertyType->dataClass)->emptyData
        ) {
            /** @var class-string<EmptyData> $dataClass */
            $dataClass = $propertyType->dataClass;

            return $dataClass::empty();
        }

        if ($propertyType->kind->isDataCollectable()) {
            return [];
        }

        if ($propertyType->type->findAcceptedTypeForBaseType(Traversable::class) !== null) {
            return [];
        }

        return null;
    }
}
