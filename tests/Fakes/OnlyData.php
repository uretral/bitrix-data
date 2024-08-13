<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Support\DataConfig;
use Uretral\BitrixData\Support\DataContainer;

class OnlyData extends Data
{
    protected static ?array $allowedOnly = null;

    public function __construct(
        public string $first_name,
        public string $last_name,
    ) {
    }

    public static function allowedRequestOnly(): ?array
    {
        return self::$allowedOnly;
    }

    public static function setAllowedOnly(?array $allowedOnly): void
    {
        self::$allowedOnly = $allowedOnly;

        // Ensure cached config is cleared
        app(DataConfig::class)->reset();
        DataContainer::get()->reset();
    }
}
