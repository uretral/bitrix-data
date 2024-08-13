<?php

use Uretral\BitrixData\Attributes\Validation\Min;
use Uretral\BitrixData\Attributes\Validation\Prohibited;
use Uretral\BitrixData\Attributes\Validation\Required;
use Uretral\BitrixData\Support\Validation\PropertyRules;

it('can add rules', function () {
    $collection = PropertyRules::create()
        ->add(new Required())
        ->add(new Prohibited(), new Min(0));

    expect($collection->all())->toMatchArray([
        new Required(), new Prohibited(), new Min(0),
    ]);
});

it('will remove the rule if a new version is added', function () {
    $collection = PropertyRules::create()
        ->add(new Min(10))
        ->add(new Min(314));

    expect($collection->all())->toEqual([new Min(314)]);
});

it('can remove rules by type', function () {
    $collection = PropertyRules::create()
        ->add(new Min(10))
        ->removeType(new Min(314));

    expect($collection->all())->toEqual([]);
});

it('can remove rules by class', function () {
    $collection = PropertyRules::create()
        ->add(new Min(10))
        ->removeType(Min::class);

    expect($collection->all())->toEqual([]);
});
