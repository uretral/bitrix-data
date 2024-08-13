<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Lazy;

class MultiLazyData extends Data
{
    public function __construct(
        public string | Lazy $artist,
        public string | Lazy $name,
        public int | Lazy $year,
    ) {
    }

    public static function fromMultiple(string $artist, string $name, int $year): static
    {
        return new self(
            Lazy::create(fn () => $artist),
            Lazy::create(fn () => $name),
            Lazy::create(fn () => $year),
        );
    }

    public static function fromDto(DummyDto $dto)
    {
        return new self(
            Lazy::create(fn () => $dto->artist),
            Lazy::create(fn () => $dto->name),
            Lazy::create(fn () => $dto->year),
        );
    }
}
