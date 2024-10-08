<?php

namespace Uretral\BitrixData\Concerns;

use ArrayIterator;
use Uretral\BitrixData\Support\Transformation\DataContext;
use Uretral\BitrixData\Support\Transformation\TransformationContextFactory;

/**
 * @template TKey of array-key
 * @template TValue
 */
trait BaseDataCollectable
{
    protected ?DataContext $_dataContext = null;

    /** @return class-string<TValue> */
    public function getDataClass(): string
    {
        return $this->dataClass;
    }

    /**  @return \ArrayIterator<TKey, TValue> */
    public function getIterator(): ArrayIterator
    {
        /** @var array<TValue> $data */
        $data = $this->transform(TransformationContextFactory::create()->withValueTransformation(false));

        return new ArrayIterator($data);
    }

    public function count(): int
    {
        return $this->items->count();
    }

    public function __sleep(): array
    {
        return ['items', 'dataClass'];
    }
}
