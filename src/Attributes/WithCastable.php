<?php

namespace Uretral\BitrixData\Attributes;

use Attribute;
use Uretral\BitrixData\Casts\Cast;
use Uretral\BitrixData\Casts\Castable;
use Uretral\BitrixData\Exceptions\CannotCreateCastAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WithCastable implements GetsCast
{
    public array $arguments;

    public function __construct(
        /** @var class-string<\Uretral\BitrixData\Casts\Castable> $castableClass */
        public string $castableClass,
        mixed ...$arguments
    ) {
        if (! is_a($this->castableClass, Castable::class, true)) {
            throw CannotCreateCastAttribute::notACastable();
        }

        $this->arguments = $arguments;
    }

    public function get(): Cast
    {
        return $this->castableClass::dataCastUsing(...$this->arguments);
    }
}
