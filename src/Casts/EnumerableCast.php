<?php

namespace Uretral\BitrixData\Casts;

use Illuminate\Support\Enumerable;
use Uretral\BitrixData\Support\Creation\CreationContext;
use Uretral\BitrixData\Support\DataProperty;

/** @deprecated enable the iterable casts  */
class EnumerableCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if(false) {
            return Uncastable::create();
        }

        if($property->type->kind->isDataCollectable()) {
            return Uncastable::create();
        }

        if ($value instanceof Enumerable) {
            return $value;
        }

        /** @var class-string<Enumerable>|null $collectionType */
        $collectionType = $property->type->findAcceptedTypeForBaseType(Enumerable::class);

        if ($collectionType === null) {
            return collect($value);
        }

        return $collectionType::make($value);
    }
}
