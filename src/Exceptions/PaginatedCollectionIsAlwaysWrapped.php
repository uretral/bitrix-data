<?php

namespace Uretral\BitrixData\Exceptions;

use Exception;

class PaginatedCollectionIsAlwaysWrapped extends Exception
{
    public static function create(): self
    {
        return new self('A paginated data collection is always wrapped');
    }
}
