<?php

namespace Uretral\BitrixData\Concerns;

use Exception;
use Uretral\BitrixData\Contracts\BaseData as BaseDataContract;
use Uretral\BitrixData\Contracts\BaseDataCollectable as BaseDataCollectableContract;
use Uretral\BitrixData\Contracts\IncludeableData as IncludeableDataContract;
use Uretral\BitrixData\Support\DataContainer;
use Uretral\BitrixData\Support\EloquentCasts\DataEloquentCast;
use Uretral\BitrixData\Support\Transformation\TransformationContext;
use Uretral\BitrixData\Support\Transformation\TransformationContextFactory;

trait TransformableData
{
    use ContextableData;

    public function transform(
        null|TransformationContextFactory|TransformationContext $transformationContext = null,
    ): array {
        $transformationContext = match (true) {
            $transformationContext instanceof TransformationContext => $transformationContext,
            $transformationContext instanceof TransformationContextFactory => $transformationContext->get($this),
            $transformationContext === null => new TransformationContext(
                maxDepth: null,
                throwWhenMaxDepthReached: true
            )
        };

        $resolver = match (true) {
            $this instanceof BaseDataContract => DataContainer::get()->transformedDataResolver(),
            $this instanceof BaseDataCollectableContract => DataContainer::get()->transformedDataCollectableResolver(),
            default => throw new Exception('Cannot transform data object')
        };

        if ($this instanceof IncludeableDataContract) {
            $transformationContext->mergePartialsFromDataContext($this);
        }

        return $resolver->execute($this, $transformationContext);
    }

    public function all(): array
    {
        return $this->transform(TransformationContextFactory::create()->withValueTransformation(false));
    }

    public function toArray(): array
    {
        return $this->transform();
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->transform(), $options);
    }

    public function jsonSerialize(): array
    {
        return $this->transform();
    }

    public static function castUsing(array $arguments)
    {
        return new DataEloquentCast(static::class, $arguments);
    }
}
