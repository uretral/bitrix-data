<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;

class ComputedData extends Data
{
    public string $name = 'computed';

    public function __construct(
        public string $first_name,
        public string $last_name,
    ) {
        $this->name = $this->first_name . ' ' . $this->last_name;
    }
}
