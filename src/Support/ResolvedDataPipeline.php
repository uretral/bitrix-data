<?php

namespace Uretral\BitrixData\Support;

use Illuminate\Container\Container;
use Uretral\BitrixData\Exceptions\CannotCreateData;
use Uretral\BitrixData\Normalizers\Normalized\Normalized;
use Uretral\BitrixData\Normalizers\Normalized\UnknownProperty;
use Uretral\BitrixData\Support\Creation\CreationContext;

class ResolvedDataPipeline
{
    /**
     * @param array<\Uretral\BitrixData\Normalizers\Normalizer> $normalizers
     * @param array<\Uretral\BitrixData\DataPipes\DataPipe> $pipes
     */
    public function __construct(
        protected array $normalizers,
        protected array $pipes,
        protected DataClass $dataClass,
    ) {
    }

    public function execute(mixed $value, CreationContext $creationContext): array
    {
        $properties = null;

        foreach ($this->normalizers as $normalizer) {

            $properties = ($normalizer)->normalize($value);

            if ($properties !== null) {
                break;
            }
        }

        if ($properties === null) {
            throw CannotCreateData::noNormalizerFound($this->dataClass->name, $value);
        }

        if (! is_array($properties)) {
            $properties = $this->transformNormalizedToArray($properties);
        }

        $properties = ($this->dataClass->name)::prepareForPipeline($properties);

        foreach ($this->pipes as $pipe) {
            $piped = $pipe->handle($value, $this->dataClass, $properties, $creationContext);

            $properties = $piped;
        }

        return $properties;
    }

    protected function transformNormalizedToArray(Normalized $normalized): array
    {
        $properties = [];

        foreach ($this->dataClass->properties as $property) {
            $name = $property->inputMappedName ?? $property->name;

            $value = $normalized->getProperty($name, $property);

            if ($value === UnknownProperty::create()) {
                continue;
            }

            $properties[$name] = $value;
        }

        return $properties;
    }
}
