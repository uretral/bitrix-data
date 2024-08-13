<?php

namespace Uretral\BitrixData\Resolvers;

use Illuminate\Support\Collection;
use Uretral\BitrixData\Attributes\MapInputName;
use Uretral\BitrixData\Attributes\MapName;
use Uretral\BitrixData\Attributes\MapOutputName;
use Uretral\BitrixData\Mappers\NameMapper;
use Uretral\BitrixData\Mappers\ProvidedNameMapper;

class NameMappersResolver
{
    public static function create(array $ignoredMappers = []): self
    {
        return new self($ignoredMappers);
    }

    public function __construct(protected array $ignoredMappers = [])
    {
    }

    public function execute(
        Collection $attributes
    ): array {
        return [
            'inputNameMapper' => $this->resolveInputNameMapper($attributes),
            'outputNameMapper' => $this->resolveOutputNameMapper($attributes),
        ];
    }

    protected function resolveInputNameMapper(
        Collection $attributes
    ): ?NameMapper {
        /** @var \Uretral\BitrixData\Attributes\MapInputName|\Uretral\BitrixData\Attributes\MapName|null $mapper */
        $mapper = $attributes->first(fn (object $attribute) => $attribute instanceof MapInputName)
            ?? $attributes->first(fn (object $attribute) => $attribute instanceof MapName);

        if ($mapper) {
            return $this->resolveMapper($mapper->input);
        }

        return null;
    }

    protected function resolveOutputNameMapper(
        Collection $attributes
    ): ?NameMapper {
        /** @var \Uretral\BitrixData\Attributes\MapOutputName|\Uretral\BitrixData\Attributes\MapName|null $mapper */
        $mapper = $attributes->first(fn (object $attribute) => $attribute instanceof MapOutputName)
            ?? $attributes->first(fn (object $attribute) => $attribute instanceof MapName);

        if ($mapper) {
            return $this->resolveMapper($mapper->output);
        }

        return null;
    }

    protected function resolveMapper(string|int|NameMapper $value): ?NameMapper
    {
        $mapper = $this->resolveMapperClass($value);

        foreach ($this->ignoredMappers as $ignoredMapper) {
            if ($mapper instanceof $ignoredMapper) {
                return null;
            }
        }

        return $mapper;
    }

    protected function resolveMapperClass(int|string|NameMapper $value): NameMapper
    {
        if (is_int($value)) {
            return new ProvidedNameMapper($value);
        }

        if($value instanceof NameMapper) {
            return $value;
        }

        if (is_a($value, NameMapper::class, true)) {
            return resolve($value);
        }

        return new ProvidedNameMapper($value);
    }
}
