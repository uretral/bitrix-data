<?php

namespace Uretral\BitrixData;

use Closure;
use Countable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Pagination\CursorPaginator;
use IteratorAggregate;
use Uretral\BitrixData\Concerns\BaseDataCollectable;
use Uretral\BitrixData\Concerns\ContextableData;
use Uretral\BitrixData\Concerns\IncludeableData;
use Uretral\BitrixData\Concerns\ResponsableData;
use Uretral\BitrixData\Concerns\TransformableData;
use Uretral\BitrixData\Concerns\WrappableData;
use Uretral\BitrixData\Contracts\BaseDataCollectable as BaseDataCollectableContract;
use Uretral\BitrixData\Contracts\IncludeableData as IncludeableDataContract;
use Uretral\BitrixData\Contracts\ResponsableData as ResponsableDataContract;
use Uretral\BitrixData\Contracts\TransformableData as TransformableDataContract;
use Uretral\BitrixData\Contracts\WrappableData as WrappableDataContract;
use Uretral\BitrixData\Exceptions\CannotCastData;
use Uretral\BitrixData\Exceptions\PaginatedCollectionIsAlwaysWrapped;
use Uretral\BitrixData\Support\EloquentCasts\DataCollectionEloquentCast;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements  IteratorAggregate<TKey, TValue>
 */
class CursorPaginatedDataCollection implements Responsable, BaseDataCollectableContract, TransformableDataContract, ResponsableDataContract, IncludeableDataContract, WrappableDataContract, IteratorAggregate, Countable
{
    use ResponsableData;
    use IncludeableData;
    use WrappableData;
    use TransformableData;

    /** @use \Uretral\BitrixData\Concerns\BaseDataCollectable<TKey, TValue> */
    use BaseDataCollectable;
    use ContextableData;

    /** @var CursorPaginator<TValue> */
    protected CursorPaginator $items;

    /**
     * @param class-string<TValue> $dataClass
     * @param CursorPaginator<TValue> $items
     */
    public function __construct(
        public readonly string $dataClass,
        CursorPaginator $items
    ) {
        $this->items = $items->through(
            fn ($item) => $item instanceof $this->dataClass ? $item : $this->dataClass::from($item)
        );
    }

    /**
     * @param Closure(TValue, TKey): TValue $through
     *
     * @return static<TKey, TValue>
     */
    public function through(Closure $through): static
    {
        $clone = clone $this;

        $clone->items = $clone->items->through($through);

        return $clone;
    }

    /**
     * @return CursorPaginator<TValue>
     */
    public function items(): CursorPaginator
    {
        return $this->items;
    }

    public static function castUsing(array $arguments)
    {
        if (count($arguments) !== 1) {
            throw CannotCastData::dataCollectionTypeRequired();
        }

        return new DataCollectionEloquentCast(current($arguments));
    }

    public function withoutWrapping(): static
    {
        throw PaginatedCollectionIsAlwaysWrapped::create();
    }
}
