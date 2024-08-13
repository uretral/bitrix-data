<?php

namespace Uretral\BitrixData\Tests\Fakes;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum;

/**
 * @property \Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum[] $propertyM
 * @property array<\Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum> $propertyN
 * @property array<DummyBackedEnum> $propertyO
 * @property array<DummyBackedEnum>|null $propertyQ
 */
class CollectionNonDataAnnotationsData
{
    /** @var \Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum[] */
    public array $propertyA;

    /** @var \Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum[]|null */
    public ?array $propertyB;

    /** @var null|\Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum[] */
    public ?array $propertyC;

    /** @var ?\Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum[] */
    public array $propertyD;

    /** @var array<string> */
    public array $propertyE;

    /** @var array<string> */
    public array $propertyF;

    /** @var DummyBackedEnum[] */
    public array $propertyG;

    /** @var DummyBackedEnum */
    public array $propertyH; // FAIL

    public array $propertyI;

    /** @var array<\Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum> */
    public array $propertyJ;

    /** @var LengthAwarePaginator<\Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum> */
    public LengthAwarePaginator $propertyK;

    /** @var \Illuminate\Support\Collection<\Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum> */
    public Collection $propertyL;

    public array $propertyM;

    public array $propertyN;

    public array $propertyO;

    /** @var \Illuminate\Support\Collection<Error> */
    public Collection $propertyP;

    public array $propertyQ;

    /** @var \Illuminate\Support\Collection<Error>|null */
    public ?Collection $propertyR;

    /**
     * @param \Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum[]|null $paramA
     * @param null|\Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum[] $paramB
     * @param  ?\Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum[] $paramC
     * @param ?\Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum[] $paramD
     * @param \Uretral\BitrixData\DataCollection<\Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum> $paramE
     * @param ?\Uretral\BitrixData\DataCollection<\Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum> $paramF
     * @param DummyBackedEnum[] $paramG
     * @param array<DummyBackedEnum> $paramH
     * @param array<int,DummyBackedEnum> $paramJ
     * @param array<int, DummyBackedEnum> $paramI
     * @param \Uretral\BitrixData\DataCollection<\Uretral\BitrixData\Tests\Fakes\Enums\DummyBackedEnum>|null $paramK
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
