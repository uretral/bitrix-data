<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Uretral\BitrixData\Attributes\DataCollectionOf;
use Uretral\BitrixData\DataCollection;

/**
 * @property DataCollection<\Uretral\BitrixData\Tests\Fakes\SimpleData> $propertyN
 * @property \Uretral\BitrixData\Tests\Fakes\SimpleData[] $propertyO
 * @property DataCollection<SimpleData> $propertyP
 * @property array<\Uretral\BitrixData\Tests\Fakes\SimpleData> $propertyQ
 * @property \Uretral\BitrixData\Tests\Fakes\SimpleData[] $propertyR
 * @property array<SimpleData> $propertyS
 * @property \Illuminate\Support\Collection<\Uretral\BitrixData\Tests\Fakes\SimpleData>|null $propertyT
 */
class CollectionDataAnnotationsData
{
    /** @var \Uretral\BitrixData\Tests\Fakes\SimpleData[] */
    public array $propertyA;

    /** @var \Uretral\BitrixData\Tests\Fakes\SimpleData[]|null */
    public ?array $propertyB;

    /** @var null|\Uretral\BitrixData\Tests\Fakes\SimpleData[] */
    public ?array $propertyC;

    /** @var ?\Uretral\BitrixData\Tests\Fakes\SimpleData[] */
    public array $propertyD;

    /** @var \Uretral\BitrixData\DataCollection<\Uretral\BitrixData\Tests\Fakes\SimpleData> */
    public DataCollection $propertyE;

    /** @var ?\Uretral\BitrixData\DataCollection<\Uretral\BitrixData\Tests\Fakes\SimpleData> */
    public ?DataCollection $propertyF;

    /** @var SimpleData[] */
    public array $propertyG;

    #[DataCollectionOf(SimpleData::class)]
    public DataCollection $propertyH;

    /** @var SimpleData */
    public DataCollection $propertyI; // FAIL

    public DataCollection $propertyJ;

    /** @var array<\Uretral\BitrixData\Tests\Fakes\SimpleData> */
    public array $propertyK;

    /** @var LengthAwarePaginator<\Uretral\BitrixData\Tests\Fakes\SimpleData> */
    public LengthAwarePaginator $propertyL;

    /** @var \Illuminate\Support\Collection<\Uretral\BitrixData\Tests\Fakes\SimpleData> */
    public Collection $propertyM;

    public DataCollection $propertyN;

    public DataCollection $propertyO;

    public DataCollection $propertyP;

    public array $propertyQ;

    public array $propertyR;

    public array $propertyS;

    public ?array $propertyT;

    /** @var \Illuminate\Support\Collection<\Uretral\BitrixData\Tests\Fakes\SimpleData>|null */
    public ?array $propertyU;

    /**
     * @param \Uretral\BitrixData\Tests\Fakes\SimpleData[]|null $paramA
     * @param null|\Uretral\BitrixData\Tests\Fakes\SimpleData[] $paramB
     * @param  ?\Uretral\BitrixData\Tests\Fakes\SimpleData[] $paramC
     * @param ?\Uretral\BitrixData\Tests\Fakes\SimpleData[] $paramD
     * @param \Uretral\BitrixData\DataCollection<\Uretral\BitrixData\Tests\Fakes\SimpleData> $paramE
     * @param ?\Uretral\BitrixData\DataCollection<\Uretral\BitrixData\Tests\Fakes\SimpleData> $paramF
     * @param SimpleData[] $paramG
     * @param array<SimpleData> $paramH
     * @param array<int,SimpleData> $paramJ
     * @param array<int, SimpleData> $paramI
     * @param \Uretral\BitrixData\DataCollection<\Uretral\BitrixData\Tests\Fakes\SimpleData>|null $paramK
     */
    public function method(
        array $paramA,
        ?array $paramB,
        ?array $paramC,
        array $paramD,
        DataCollection $paramE,
        ?DataCollection $paramF,
        array $paramG,
        array $paramJ,
        array $paramI,
        ?array $paramK,
    ) {

    }
}
