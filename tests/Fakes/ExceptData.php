<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Support\DataConfig;
use Uretral\BitrixData\Support\DataContainer;

class ExceptData extends Data
{
    protected static ?array $allowedExcept = null;

    public function __construct(
        public string $first_name,
        public string $last_name,
    ) {
    }

    public static function allowedRequestExcept(): ?array
    {
        return self::$allowedExcept;
    }

    public static function setAllowedExcept(?array $allowedExcept): void
    {
        self::$allowedExcept = $allowedExcept;

        // Ensure cached config is cleared
        app(DataConfig::class)->reset();
        DataContainer::get()->reset();
    }
}
