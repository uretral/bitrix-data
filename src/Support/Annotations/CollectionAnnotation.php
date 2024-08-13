<?php

namespace Uretral\BitrixData\Support\Annotations;

class CollectionAnnotation
{
    public function __construct(
        public string $type,
        public bool $isData,
        public string $keyType = 'array-key',
    ) {
    }
}
