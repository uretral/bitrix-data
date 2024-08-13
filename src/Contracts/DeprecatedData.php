<?php

namespace Uretral\BitrixData\Contracts;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Enumerable;
use Uretral\BitrixData\CursorPaginatedDataCollection;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\PaginatedDataCollection;

/**
 * @template TValue
 */
interface DeprecatedData
{
    /**
     * @param \Illuminate\Support\Enumerable<array-key, TValue>|TValue[]|\Illuminate\Pagination\AbstractPaginator|\Illuminate\Contracts\Pagination\Paginator|\Illuminate\Pagination\AbstractCursorPaginator|\Illuminate\Contracts\Pagination\CursorPaginator|\Uretral\BitrixData\DataCollection<array-key, TValue> $items
     *
     * @return ($items is \Illuminate\Pagination\AbstractCursorPaginator|\Illuminate\Pagination\CursorPaginator ? \Uretral\BitrixData\CursorPaginatedDataCollection<array-key, static> : ($items is \Illuminate\Pagination\Paginator|\Illuminate\Pagination\AbstractPaginator ? \Uretral\BitrixData\PaginatedDataCollection<array-key, static> : \Uretral\BitrixData\DataCollection<array-key, static>))
     */
    public static function collection(Enumerable|array|AbstractPaginator|Paginator|AbstractCursorPaginator|CursorPaginator|DataCollection $items): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection;
}
