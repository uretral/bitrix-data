<?php

namespace Uretral\BitrixData\Contracts;

interface AppendableData
{
    public function with(): array;

    public function additional(array $additional): object;

    public function getAdditionalData(): array;
}
