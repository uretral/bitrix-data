<?php

namespace Uretral\BitrixData\Support\Livewire;

use Illuminate\Container\Container;
use Livewire\Mechanisms\HandleComponents\ComponentContext;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
use Uretral\BitrixData\Contracts\BaseData;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\Support\DataConfig;

class LivewireDataCollectionSynth extends Synth
{
    protected DataConfig $dataConfig;

    public static string $key = 'ldco';

    public function __construct(ComponentContext $context, $path)
    {
        $this->dataConfig =  Container::getInstance()->make(DataConfig::class);

        parent::__construct($context, $path);
    }

    public static function match($target): bool
    {
        return is_a($target, DataCollection::class, true);
    }

    public function get(&$target, $key): BaseData
    {
        return $target[$key];
    }

    public function set(&$target, $key, $value)
    {
        $target[$key] = $value;
    }

    /**
     * @param callable(array-key, mixed):mixed $dehydrateChild
     */
    public function dehydrate(DataCollection $target, callable $dehydrateChild): array
    {
        $morph = $this->dataConfig->morphMap->getDataClassAlias($target->dataClass) ?? $target->dataClass;

        $payload = [];

        foreach ($target->toCollection() as $key => $child) {
            $payload[$key] = $dehydrateChild($key, $child);
        }

        return [
            $payload,
            [
                'dataCollectionClass' => $target::class,
                'dataMorph' => $morph,
                'context' => encrypt($target->getDataContext()),
            ],
        ];
    }

    /**
     * @param callable(array-key, mixed):mixed $hydrateChild
     */
    public function hydrate($value, $meta, $hydrateChild)
    {
        $context = decrypt($meta['context']);
        $dataCollectionClass = $meta['dataCollectionClass'];
        $dataClass = $this->dataConfig->morphMap->getMorphedDataClass($meta['dataMorph']) ?? $meta['dataMorph'];

        foreach ($value as $key => $child) {
            $value[$key] = $hydrateChild($key, $child);
        }

        /** @var DataCollection $dataCollection */
        $dataCollection = new $dataCollectionClass($dataClass, $value);

        $dataCollection->setDataContext($context);

        return $dataCollection;
    }
}
