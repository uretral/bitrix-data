<?php

namespace Uretral\BitrixData\Concerns;

use Illuminate\Container\Container;
use Illuminate\Contracts\Pagination\CursorPaginator as CursorPaginatorContract;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;
use Uretral\BitrixData\CursorPaginatedDataCollection;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\DataPipeline;
use Uretral\BitrixData\DataPipes\AuthorizedDataPipe;
use Uretral\BitrixData\DataPipes\CastPropertiesDataPipe;
use Uretral\BitrixData\DataPipes\DefaultValuesDataPipe;
use Uretral\BitrixData\DataPipes\FillRouteParameterPropertiesDataPipe;
use Uretral\BitrixData\DataPipes\MapPropertiesDataPipe;
use Uretral\BitrixData\DataPipes\ValidatePropertiesDataPipe;
use Uretral\BitrixData\PaginatedDataCollection;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\Creation\CreationContextFactory;
use Uretral\BitrixData\Support\DataConfig;
use Uretral\BitrixData\Support\DataProperty;

trait BaseData
{
    public static function optional(mixed ...$payloads): ?static
    {
        if (count($payloads) === 0) {
            return null;
        }

        foreach ($payloads as $payload) {
            if ($payload !== null) {
                return static::from(...$payloads);
            }
        }

        return null;
    }

    public static function from(mixed ...$payloads): static
    {
        return static::factory()->from(...$payloads);
    }

    public static function collect(mixed $items, ?string $into = null): array|DataCollection|PaginatedDataCollection|CursorPaginatedDataCollection|Enumerable|AbstractPaginator|PaginatorContract|AbstractCursorPaginator|CursorPaginatorContract|LazyCollection|Collection
    {
        return static::factory()->collect($items, $into);
    }

    public static function factory(?CreationContext $creationContext = null): CreationContextFactory
    {
        if ($creationContext) {
            return CreationContextFactory::createFromCreationContext(static::class, $creationContext);
        }

        return CreationContextFactory::createFromConfig(static::class);
    }

    public static function normalizers(): array
    {
        return  [
            \Uretral\BitrixData\Normalizers\ModelNormalizer::class,
            // Uretral\BitrixData\Normalizers\FormRequestNormalizer::class,
            \Uretral\BitrixData\Normalizers\ArrayableNormalizer::class,
            \Uretral\BitrixData\Normalizers\ObjectNormalizer::class,
            \Uretral\BitrixData\Normalizers\ArrayNormalizer::class,
            \Uretral\BitrixData\Normalizers\JsonNormalizer::class,
        ];
    }

    public static function pipeline(): DataPipeline
    {
        return DataPipeline::create()
            ->into(static::class)
            ->through(AuthorizedDataPipe::class)
            ->through(MapPropertiesDataPipe::class)
            ->through(FillRouteParameterPropertiesDataPipe::class)
            ->through(ValidatePropertiesDataPipe::class)
            ->through(DefaultValuesDataPipe::class)
            ->through(CastPropertiesDataPipe::class);
    }

    public static function prepareForPipeline(array $properties): array
    {
        return $properties;
    }

    public function __sleep(): array
    {
        $dataClass =  Container::getInstance()->make(DataConfig::class)->getDataClass(static::class);

        return $dataClass
            ->properties
            ->map(fn (DataProperty $property) => $property->name)
            ->when($dataClass->appendable, fn (Collection $properties) => $properties->push('_additional'))
            ->when(property_exists($this, '_dataContext'), fn (Collection $properties) => $properties->push('_dataContext'))
            ->toArray();
    }
}
