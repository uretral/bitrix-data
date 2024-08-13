<?php

namespace Uretral\BitrixData\Tests\Factories;

use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Uretral\BitrixData\Support\DataClass;
use Uretral\BitrixData\Support\DataMethod;
use Uretral\BitrixData\Support\DataParameter;
use Uretral\BitrixData\Support\DataProperty;
use Uretral\BitrixData\Support\Factories\DataClassFactory;
use Uretral\BitrixData\Support\Factories\DataMethodFactory;
use Uretral\BitrixData\Support\Factories\DataParameterFactory;
use Uretral\BitrixData\Support\Factories\DataPropertyFactory;
use Uretral\BitrixData\Support\Factories\DataReturnTypeFactory;
use Uretral\BitrixData\Support\Factories\DataTypeFactory;

class FakeDataStructureFactory
{
    protected static DataClassFactory $dataClassFactory;

    protected static DataMethodFactory $methodFactory;

    protected static DataPropertyFactory $propertyFactory;
    protected static DataParameterFactory $parameterFactory;

    protected static DataTypeFactory $typeFactory;

    protected static DataReturnTypeFactory $returnTypeFactory;

    public static function class(
        object|string $class,
    ): DataClass {
        if (! $class instanceof ReflectionClass) {
            $class = new ReflectionClass($class);
        }

        $factory = static::$dataClassFactory ??= app(DataClassFactory::class);

        return $factory->build($class);
    }

    public static function method(
        ReflectionMethod $method,
    ): DataMethod {
        $factory = static::$methodFactory ??= app(DataMethodFactory::class);

        return $factory->build($method, $method->getDeclaringClass());
    }

    public static function constructor(
        ReflectionMethod $method,
        Collection $properties
    ): DataMethod {
        $factory = static::$methodFactory ??= app(DataMethodFactory::class);

        return $factory->buildConstructor($method, $method->getDeclaringClass(), $properties);
    }

    public static function property(
        object $class,
        string $name,
    ): DataProperty {
        $reflectionClass = new ReflectionClass($class);
        $reflectionProperty = new ReflectionProperty($class, $name);

        $factory = static::$propertyFactory ??= app(DataPropertyFactory::class);

        return $factory->build($reflectionProperty, $reflectionClass);
    }

    public static function parameter(
        ReflectionParameter $parameter,
    ): DataParameter {
        $factory = static::$parameterFactory ??= app(DataParameterFactory::class);

        return $factory->build($parameter, $parameter->getDeclaringClass());
    }
}
