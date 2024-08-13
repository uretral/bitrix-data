<?php

namespace Uretral\BitrixData\Casts;

use BackedEnum;
use Uretral\BitrixData\Exceptions\CannotCastEnum;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;
use Throwable;

class EnumCast implements Cast, IterableItemCast
{
    public function __construct(
        protected ?string $type = null
    ) {
    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): BackedEnum|Uncastable
    {
        return $this->castValue(
            $this->type ?? $property->type->type->findAcceptedTypeForBaseType(BackedEnum::class),
            $value
        );
    }

    public function castIterableItem(DataProperty $property, mixed $value, array $properties, CreationContext $context): BackedEnum|Uncastable
    {
        return $this->castValue($property->type->iterableItemType, $value);
    }

    protected function castValue(
        ?string $type,
        mixed $value
    ): BackedEnum|Uncastable {
        if ($type === null) {
            return Uncastable::create();
        }

        /** @var class-string<\BackedEnum> $type */
        try {
            return $type::from($value);
        } catch (Throwable $e) {
            throw CannotCastEnum::create($type, $value);
        }
    }
}
