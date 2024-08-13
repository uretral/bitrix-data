<?php

namespace Uretral\BitrixData\Resolvers\Concerns;

use Uretral\BitrixData\Exceptions\MaxTransformationDepthReached;
use Uretral\BitrixData\Support\Transformation\TransformationContext;

trait ChecksTransformationDepth
{
    public function hasReachedMaxTransformationDepth(TransformationContext $context): bool
    {
        return $context->maxDepth !== null && $context->depth >= $context->maxDepth;
    }

    public function handleMaxDepthReached(TransformationContext $context): array
    {
        if ($context->throwWhenMaxDepthReached) {
            throw MaxTransformationDepthReached::create($context->maxDepth);
        }

        return [];
    }
}
