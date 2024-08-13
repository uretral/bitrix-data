<?php

namespace Uretral\BitrixData;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use IteratorAggregate;
use Uretral\BitrixData\Concerns\BaseDataCollectable;
use Uretral\BitrixData\Concerns\ContextableData;
use Uretral\BitrixData\Concerns\EnumerableMethods;
use Uretral\BitrixData\Concerns\IncludeableData;
use Uretral\BitrixData\Concerns\ResponsableData;
use Uretral\BitrixData\Concerns\TransformableData;
use Uretral\BitrixData\Concerns\WrappableData;
use Uretral\BitrixData\Contracts\BaseData;
use Uretral\BitrixData\Contracts\BaseDataCollectable as BaseDataCollectableContract;
use Uretral\BitrixData\Contracts\IncludeableData as IncludeableDataContract;
use Uretral\BitrixData\Contracts\ResponsableData as ResponsableDataContract;
use Uretral\BitrixData\Contracts\TransformableData as TransformableDataContract;
use Uretral\BitrixData\Contracts\WrappableData as WrappableDataContract;
use Uretral\BitrixData\Exceptions\CannotCastData;
use Uretral\BitrixData\Exceptions\InvalidDataCollectionOperation;
use Uretral\BitrixData\Support\EloquentCasts\DataCollectionEloquentCast;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements \ArrayAccess<TKey, TValue>
 * @implements  IteratorAggregate<TKey, TValue>
 */
class DataCollection implements Responsable, BaseDataCollectableContract, TransformableDataContract, ResponsableDataContract, IncludeableDataContract, WrappableDataContract, IteratorAggregate, Countable, ArrayAccess
{
    /** @use \Uretral\BitrixData\Concerns\BaseDataCollectable<TKey, TValue> */
    use BaseDataCollectable;
    use ResponsableData;
    use IncludeableData;
    use WrappableData;
    use TransformableData;
    use ContextableData;

    /** @use \Uretral\BitrixData\Concerns\EnumerableMethods<TKey, TValue> */
    use EnumerableMethods;

    /** @var Enumerable<TKey, TValue> */
    protected Enumerable $items;

    /**
     * @param class-string<TValue> $dataClass
     * @param array|Enumerable<TKey, TValue>|DataCollection $items
     */
    public function __construct(
        public readonly string $dataClass,
        Enumerable|array|DataCollection|null $items
    ) {
        if (is_array($items) || is_null($items)) {
            $items = new Collection($items);
        }

        if ($items instanceof DataCollection) {
            $items = $items->toCollection();
        }

        $this->items = $items->map(
            fn ($item) => $item instanceof $this->dataClass ? $item : $this->dataClass::from($item)
        );
    }

    /**
     * @return array<TKey, TValue>
     */
    public function items(): array
    {
        return $this->items->all();
    }

    /**
     * @return Enumerable<TKey, TValue>
     */
    public function toCollection(): Enumerable
    {
        return $this->items;
    }

    /**
     * @param TKey $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        if (! $this->items instanceof ArrayAccess) {
            throw InvalidDataCollectionOperation::create();
        }

        return $this->items->offsetExists($offset);
    }

    /**
     * @param TKey $offset
     *
     * @return TValue
     */
    public function offsetGet($offset): mixed
    {
        if (! $this->items instanceof ArrayAccess) {
            throw InvalidDataCollectionOperation::create();
        }

        $data = $this->items->offsetGet($offset);

        if ($data instanceof IncludeableDataContract) {
            $data->getDataContext()->mergePartials($this->getDataContext());
        }

        return $data;
    }

    /**
     * @param TKey|null $offset
     * @param TValue $value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (! $this->items instanceof ArrayAccess) {
            throw InvalidDataCollectionOperation::create();
        }

        $value = $value instanceof BaseData
            ? $value
            : $this->dataClass::from($value);

        $this->items->offsetSet($offset, $value);
    }

    /**
     * @param TKey $offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        if (! $this->items instanceof ArrayAccess) {
            throw InvalidDataCollectionOperation::create();
        }

        $this->items->offsetUnset($offset);
    }

    public static function castUsing(array $arguments)
    {
        if (count($arguments) < 1) {
            throw CannotCastData::dataCollectionTypeRequired();
        }

        return new DataCollectionEloquentCast($arguments[0], static::class, array_slice($arguments, 1));
    }
}
