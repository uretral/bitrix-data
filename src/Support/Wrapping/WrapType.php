<?php

namespace Uretral\BitrixData\Support\Wrapping;

enum WrapType: string
{
    case UseGlobal = 'use_global';
    case Disabled = 'disabled';
    case Defined = 'defined';
}
