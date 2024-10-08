<?php

namespace Uretral\BitrixData\Support\EloquentCasts;

use Illuminate\Container\Container;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Crypt;
use Uretral\BitrixData\Contracts\BaseData;
use Uretral\BitrixData\Contracts\BaseDataCollectable;
use Uretral\BitrixData\Contracts\TransformableData;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\Exceptions\CannotCastData;
use Uretral\BitrixData\Support\DataConfig;

class DataCollectionEloquentCast implements CastsAttributes
{
    protected DataConfig $dataConfig;

    public function __construct(
        protected string $dataClass,
        protected string $dataCollectionClass = DataCollection::class,
        protected array $arguments = []
    ) {
        $this->dataConfig =  Container::getInstance()->make(DataConfig::class);
    }

    public function get($model, string $key, $value, array $attributes): ?DataCollection
    {
        if (is_string($value) && in_array('encrypted', $this->arguments)) {
            $value = Crypt::decryptString($value);
        }

        if ($value === null && in_array('default', $this->arguments)) {
            $value = '[]';
        }

        if ($value === null) {
            return null;
        }

        $data = json_decode($value, true, flags: JSON_THROW_ON_ERROR);

        $dataClass = $this->dataConfig->getDataClass($this->dataClass);

        $data = array_map(function (array $item) use ($dataClass) {
            if ($dataClass->isAbstract && $dataClass->transformable) {
                $morphedClass = $this->dataConfig->morphMap->getMorphedDataClass($item['type']) ?? $item['type'];

                return $morphedClass::from($item['data']);
            }

            return ($this->dataClass)::from($item);
        }, $data);

        return new ($this->dataCollectionClass)($this->dataClass, $data);
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof BaseDataCollectable && $value instanceof TransformableData) {
            $value = $value->all();
        }

        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        }

        if (! is_array($value)) {
            throw CannotCastData::shouldBeArray($model::class, $key);
        }

        $dataClass = $this->dataConfig->getDataClass($this->dataClass);

        $data = array_map(function (array|BaseData $item) use ($dataClass) {
            if ($dataClass->isAbstract && $item instanceof TransformableData) {
                $class = get_class($item);

                return [
                    'type' => $this->dataConfig->morphMap->getDataClassAlias($class) ?? $class,
                    'data' => json_decode(json: $item->toJson(), associative: true, flags: JSON_THROW_ON_ERROR),
                ];
            }

            return is_array($item)
                ? ($this->dataClass)::from($item)
                : $item;
        }, $value);

        if ($dataClass->isAbstract) {
            return json_encode($data);
        }

        $dataCollection = new ($this->dataCollectionClass)($this->dataClass, $data);

        $dataCollection = $dataCollection->toJson();

        if (in_array('encrypted', $this->arguments)) {
            return Crypt::encryptString($dataCollection);
        }

        return $dataCollection;
    }

    protected function isAbstractClassCast(): bool
    {
        return $this->dataConfig->getDataClass($this->dataClass)->isAbstract;
    }
}
