<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Uretral\BitrixData\Data;
use Uretral\BitrixData\Lazy;

class PartialClassConditionalData extends Data
{
    public static array $includeDefinitions = [];

    public static array $excludeDefinitions = [];

    public static array $onlyDefinitions = [];

    public static array $exceptDefinitions = [];

    public static function setDefinitions(
        array $includeDefinitions = [],
        array $excludeDefinitions = [],
        array $onlyDefinitions = [],
        array $exceptDefinitions = []
    ) {
        static::$includeDefinitions = $includeDefinitions;
        static::$excludeDefinitions = $excludeDefinitions;
        static::$onlyDefinitions = $onlyDefinitions;
        static::$exceptDefinitions = $exceptDefinitions;
    }

    public function __construct(
        public bool $enabled,
        public Lazy|string $string,
        public Lazy|SimpleData $nested,
    ) {
    }

    protected function includeProperties(): array
    {
        return self::$includeDefinitions;
    }

    protected function excludeProperties(): array
    {
        return self::$excludeDefinitions;
    }

    protected function exceptProperties(): array
    {
        return self::$exceptDefinitions;
    }

    protected function onlyProperties(): array
    {
        return self::$onlyDefinitions;
    }

    public static function create(bool $enabled): self
    {
        return new self(
            $enabled,
            'Hello World',
            SimpleData::from('Hello World')
        );
    }

    public static function createLazy(bool $enabled): self
    {
        return new self(
            $enabled,
            Lazy::create(fn () => 'Hello World'),
            Lazy::create(fn () => SimpleData::from('Hello World'))
        );
    }

    public static function createDefaultIncluded(bool $enabled)
    {
        return new self(
            $enabled,
            Lazy::create(fn () => 'Hello World')->defaultIncluded(),
            Lazy::create(fn () => SimpleData::from('Hello World'))->defaultIncluded()
        );
    }
}
