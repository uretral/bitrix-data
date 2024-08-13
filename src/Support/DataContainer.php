<?php

namespace Spatie\LaravelData\Support;

use Illuminate\Container\Container;
use Spatie\LaravelData\Resolvers\DataCollectableFromSomethingResolver;
use Spatie\LaravelData\Resolvers\DataFromSomethingResolver;
use Spatie\LaravelData\Resolvers\DataValidatorResolver;
use Spatie\LaravelData\Resolvers\DecoupledPartialResolver;
use Spatie\LaravelData\Resolvers\RequestQueryStringPartialsResolver;
use Spatie\LaravelData\Resolvers\TransformedDataCollectableResolver;
use Spatie\LaravelData\Resolvers\TransformedDataResolver;
use Spatie\LaravelData\Resolvers\ValidatedPayloadResolver;
use Spatie\LaravelData\Support\Factories\DataClassFactory;


class DataContainer
{
    protected static self $instance;

    protected ?TransformedDataResolver $transformedDataResolver = null;

    protected ?TransformedDataCollectableResolver $transformedDataCollectableResolver = null;

    protected ?RequestQueryStringPartialsResolver $requestQueryStringPartialsResolver = null;

    protected ?DataFromSomethingResolver $dataFromSomethingResolver = null;

    protected ?DataCollectableFromSomethingResolver $dataCollectableFromSomethingResolver = null;

    protected ?DataValidatorResolver $dataValidatorResolver = null;

    protected ?ValidatedPayloadResolver $validatedPayloadResolver = null;

    protected ?DataClassFactory $dataClassFactory = null;

    protected ?DecoupledPartialResolver $decoupledPartialResolver = null;

    private function __construct()
    {
    }

    public static function get(): DataContainer
    {
        if (! isset(static::$instance)) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function transformedDataResolver(): TransformedDataResolver
    {
        return $this->transformedDataResolver ??=  Container::getInstance()->make(TransformedDataResolver::class);
    }

    public function transformedDataCollectableResolver(): TransformedDataCollectableResolver
    {
        return $this->transformedDataCollectableResolver ??=  Container::getInstance()->make(TransformedDataCollectableResolver::class);
    }

    public function requestQueryStringPartialsResolver(): RequestQueryStringPartialsResolver
    {
        return $this->requestQueryStringPartialsResolver ??=  Container::getInstance()->make(RequestQueryStringPartialsResolver::class);
    }

    public function dataFromSomethingResolver(): DataFromSomethingResolver
    {
        $container = Container::getInstance();
        return $this->dataFromSomethingResolver ??= $container->make(DataFromSomethingResolver::class);
    }

    public function dataValidatorResolver(): DataValidatorResolver
    {
        return $this->dataValidatorResolver ??=  Container::getInstance()->make(DataValidatorResolver::class);
    }

    public function validatedPayloadResolver(): ValidatedPayloadResolver
    {
        return $this->validatedPayloadResolver ??=  Container::getInstance()->make(ValidatedPayloadResolver::class);
    }

    public function dataCollectableFromSomethingResolver(): DataCollectableFromSomethingResolver
    {
        return $this->dataCollectableFromSomethingResolver ??=  Container::getInstance()->make(DataCollectableFromSomethingResolver::class);
    }

    public function dataClassFactory(): DataClassFactory
    {
        $container = Container::getInstance();
        return $this->dataClassFactory ??= $container->make(DataClassFactory::class);
    }

    public function decoupledPartialResolver(): DecoupledPartialResolver
    {
        return $this->decoupledPartialResolver ??=  Container::getInstance()->make(DecoupledPartialResolver::class);
    }

    public function reset()
    {
        $this->transformedDataResolver = null;
        $this->transformedDataCollectableResolver = null;
        $this->requestQueryStringPartialsResolver = null;
        $this->dataFromSomethingResolver = null;
        $this->dataCollectableFromSomethingResolver = null;
        $this->dataClassFactory = null;
        $this->decoupledPartialResolver = null;
    }
}
