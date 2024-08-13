<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Lazy;
use Uretral\BitrixData\Support\DataConfig;
use Uretral\BitrixData\Support\DataContainer;

class DefaultLazyData extends Data
{
    protected static ?array $allowedExcludes = null;

    public function __construct(
        public string | Lazy $name
    ) {
    }

    public static function fromString(string $name): static
    {
        return new self(
            Lazy::create(fn () => $name)->defaultIncluded()
        );
    }

    public static function allowedRequestExcludes(): ?array
    {
        return self::$allowedExcludes;
    }

    public static function setAllowedExcludes(?array $allowedExcludes): void
    {
        self::$allowedExcludes = $allowedExcludes;

        // Ensure cached config is cleared
        app(DataConfig::class)->reset();
        DataContainer::get()->reset();
    }
}
