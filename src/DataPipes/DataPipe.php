<?php

namespace Uretral\BitrixData\DataPipes;

use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataClass;

interface DataPipe
{
    /**
     * @param array<array-key, mixed> $properties
     *
     * @return array<array-key, mixed>
     */
    public function handle(mixed $payload, DataClass $class, array $properties, CreationContext $creationContext): array;
}
