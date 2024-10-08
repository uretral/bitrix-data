<?php

namespace Uretral\BitrixData\Support;

use Illuminate\Container\Container;
use ReflectionClass;
use Uretral\BitrixData\Contracts\BaseData;
use Uretral\BitrixData\RuleInferrers\RuleInferrer;
use Uretral\BitrixData\Support\Creation\GlobalCastsCollection;
use Uretral\BitrixData\Support\Transformation\GlobalTransformersCollection;

class DataConfig
{
    public static function createFromConfig(array $config): static
    {
        $dataClasses = [];

        $ruleInferrers = array_map(
            fn (string $ruleInferrerClass) =>  Container::getInstance()->make($ruleInferrerClass),
            $config['rule_inferrers'] ?? []
        );

        $transformers = new GlobalTransformersCollection();

        foreach ($config['transformers'] ?? [] as $transformable => $transformer) {
            $transformers->add($transformable,  Container::getInstance()->make($transformer));
        }

        $casts = new GlobalCastsCollection();

        foreach ($config['casts'] ?? [] as $castable => $cast) {
            $casts->add($castable,  Container::getInstance()->make($cast));
        }

        $morphMap = new DataClassMorphMap();

        return new static(
            $transformers,
            $casts,
            $ruleInferrers,
            $morphMap,
            $dataClasses,
        );
    }

    /**
     * @param array<string, DataClass> $dataClasses
     * @param array<string, ResolvedDataPipeline> $resolvedDataPipelines
     * @param RuleInferrer[] $ruleInferrers
     */
    public function __construct(
        public readonly GlobalTransformersCollection $transformers = new GlobalTransformersCollection(),
        public readonly GlobalCastsCollection $casts = new GlobalCastsCollection(),
        public readonly array $ruleInferrers = [],
        public readonly DataClassMorphMap $morphMap = new DataClassMorphMap(),
        protected array $dataClasses = [],
        protected array $resolvedDataPipelines = [],
    ) {
    }

    public function getDataClass(string $class): DataClass
    {
        return $this->dataClasses[$class] ??= DataContainer::get()->dataClassFactory()->build(new ReflectionClass($class));
    }

    /**
     * @param class-string<BaseData> $class
     */
    public function getResolvedDataPipeline(string $class): ResolvedDataPipeline
    {
        return $this->resolvedDataPipelines[$class] ??= $class::pipeline()->resolve();
    }

    /**
     * @param array<string, class-string<BaseData>> $map
     */
    public function enforceMorphMap(array $map): void
    {
        $this->morphMap->merge($map);
    }

    public function reset(): self
    {
        $this->dataClasses = [];
        $this->resolvedDataPipelines = [];

        return $this;
    }
}
