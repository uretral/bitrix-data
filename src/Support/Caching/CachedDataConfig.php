<?php

namespace Uretral\BitrixData\Support\Caching;

use Uretral\BitrixData\Support\DataClass;
use Uretral\BitrixData\Support\DataConfig;

class CachedDataConfig extends DataConfig
{
    protected ?DataStructureCache $cache = null;

    public function getDataClass(string $class): DataClass
    {
        if (array_key_exists($class, $this->dataClasses)) {
            return $this->dataClasses[$class];
        }

        if ($this->cache === null) {
            return parent::getDataClass($class);
        }

        $dataClass = $this->cache->getDataClass($class);

        if ($dataClass === null) {
            return parent::getDataClass($class);
        }

        return $this->dataClasses[$class] = $dataClass;
    }

    public function setCache(DataStructureCache $cache): self
    {
        $this->cache = $cache;

        return $this;
    }
}
