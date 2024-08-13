<?php

namespace Uretral\BitrixData\DataPipes;

use Illuminate\Http\Request;
use Uretral\BitrixData\Attributes\FromRouteParameter;
use Uretral\BitrixData\Attributes\FromRouteParameterProperty;
use Uretral\BitrixData\Exceptions\CannotFillFromRouteParameterPropertyUsingScalarValue;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataClass;
use Uretral\BitrixData\Support\DataProperty;

class FillRouteParameterPropertiesDataPipe implements DataPipe
{
    public function handle(
        mixed $payload,
        DataClass $class,
        array $properties,
        CreationContext $creationContext
    ): array {
        if (! $payload instanceof Request) {
            return $properties;
        }

        foreach ($class->properties as $dataProperty) {
            $attribute = $dataProperty->attributes->first(
                fn (object $attribute) => $attribute instanceof FromRouteParameter || $attribute instanceof FromRouteParameterProperty
            );

            if ($attribute === null) {
                continue;
            }

            // if inputMappedName exists, use it first
            $name = $dataProperty->inputMappedName ?: $dataProperty->name;
            if (! $attribute->replaceWhenPresentInBody && array_key_exists($name, $properties)) {
                continue;
            }

            $parameter = $payload->route($attribute->routeParameter);

            if ($parameter === null) {
                continue;
            }

            $properties[$name] = $this->resolveValue($dataProperty, $attribute, $parameter);

            // keep the original property name
            if ($name !== $dataProperty->name) {
                $properties[$dataProperty->name] = $properties[$name];
            }
        }

        return $properties;
    }

    protected function resolveValue(
        DataProperty $dataProperty,
        FromRouteParameter|FromRouteParameterProperty $attribute,
        mixed $parameter,
    ): mixed {
        if ($attribute instanceof FromRouteParameter) {
            return $parameter;
        }

        if (is_scalar($parameter)) {
            throw CannotFillFromRouteParameterPropertyUsingScalarValue::create($dataProperty, $attribute, $parameter);
        }

        return data_get($parameter, $attribute->property ?? $dataProperty->name);
    }
}
