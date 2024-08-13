<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Lazy;
use Uretral\BitrixData\Support\DataConfig;
use Uretral\BitrixData\Support\DataContainer;

class LazyData extends Data
{
    protected static ?array $allowedIncludes = null;

    public function __construct(
        public string|Lazy $name
    ) {
    }

    public static function fromString(string $name): static
    {
        return new self(Lazy::create(fn () => $name));
    }

    public static function allowedRequestIncludes(): ?array
    {
        return self::$allowedIncludes;
    }

    public static function setAllowedIncludes(?array $allowedIncludes): void
    {
        self::$allowedIncludes = $allowedIncludes;

        // Ensure cached config is cleared
        app(DataConfig::class)->reset();
        DataContainer::get()->reset();
    }
}
