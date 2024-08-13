<?php

namespace Uretral\BitrixData\Attributes;

use Attribute;
use Uretral\BitrixData\Casts\Cast;
use Uretral\BitrixData\Exceptions\CannotCreateCastAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WithCast implements GetsCast
{
    public array $arguments;

    public function __construct(
        /** @var class-string<\Uretral\BitrixData\Casts\Cast> $castClass */
        public string $castClass,
        mixed ...$arguments
    ) {
        if (! is_a($this->castClass, Cast::class, true)) {
            throw CannotCreateCastAttribute::notACast();
        }

        $this->arguments = $arguments;
    }

    public function get(): Cast
    {
        return new ($this->castClass)(...$this->arguments);
    }
}
