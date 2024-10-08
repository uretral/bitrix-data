<?php

namespace Uretral\BitrixData\Support\EloquentCasts;

use Illuminate\Container\Container;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt;
use Uretral\BitrixData\Contracts\BaseData;
use Uretral\BitrixData\Contracts\TransformableData;
use Uretral\BitrixData\Exceptions\CannotCastData;
use Uretral\BitrixData\Support\DataConfig;

class DataEloquentCast implements CastsAttributes
{
    protected DataConfig $dataConfig;

    public function __construct(
        /** @var class-string<\Uretral\BitrixData\Contracts\BaseData> $dataClass */
        protected string $dataClass,
        /** @var string[] $arguments */
        protected array $arguments = []
    ) {
        $this->dataConfig =  Container::getInstance()->make(DataConfig::class);
    }

    public function get($model, string $key, $value, array $attributes): ?BaseData
    {
        if (is_string($value) && in_array('encrypted', $this->arguments)) {
            $value = Crypt::decryptString($value);
        }

        if (is_null($value) && in_array('default', $this->arguments)) {
            $value = '{}';
        }

        if ($value === null) {
            return null;
        }

        $payload = json_decode($value, true, flags: JSON_THROW_ON_ERROR);

        if ($this->isAbstractClassCast()) {
            /** @var class-string<BaseData> $dataClass */
            $dataClass = $this->dataConfig->morphMap->getMorphedDataClass($payload['type']) ?? $payload['type'];

            return $dataClass::from($payload['data']);
        }

        return ($this->dataClass)::from($payload);
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        $isAbstractClassCast = $this->isAbstractClassCast();

        if (is_array($value) && ! $isAbstractClassCast) {
            $value = ($this->dataClass)::from($value);
        }

        if (! $value instanceof BaseData) {
            throw CannotCastData::shouldBeData($model::class, $key);
        }

        if (! $value instanceof TransformableData) {
            throw CannotCastData::shouldBeTransformableData($model::class, $key);
        }

        $value = $isAbstractClassCast
            ? json_encode([
                'type' => $this->dataConfig->morphMap->getDataClassAlias($value::class) ?? $value::class,
                'data' => json_decode($value->toJson(), associative: true, flags: JSON_THROW_ON_ERROR),
            ])
            : $value->toJson();
        ;

        if (in_array('encrypted', $this->arguments)) {
            return Crypt::encryptString($value);
        }

        return $value;
    }

    protected function isAbstractClassCast(): bool
    {
        return $this->dataConfig->getDataClass($this->dataClass)->isAbstract;
    }
}
