<?php

namespace Uretral\BitrixData\Enums;

enum DataCollectableType
{
    case Default;
    case Array;
    case Collection;
    case Paginated;
    case CursorPaginated;
}
