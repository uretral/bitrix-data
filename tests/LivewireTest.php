<?php

use Livewire\Livewire;
use Livewire\LivewireServiceProvider;

use function Pest\Livewire\livewire;

use Uretral\BitrixData\Concerns\WireableData;
use Uretral\BitrixData\Data;

use Uretral\BitrixData\Lazy;
use Uretral\BitrixData\Support\Livewire\LivewireDataCollectionSynth;
use Uretral\BitrixData\Support\Livewire\LivewireDataSynth;
use Uretral\BitrixData\Tests\Fakes\Livewire\ComputedDataComponent;
use Uretral\BitrixData\Tests\Fakes\Livewire\DataCollectionComponent;
use Uretral\BitrixData\Tests\Fakes\Livewire\MappedDataComponent;
use Uretral\BitrixData\Tests\Fakes\Livewire\NestedDataComponent;
use Uretral\BitrixData\Tests\Fakes\Livewire\SimpleDataComponent;
use Uretral\BitrixData\Tests\Fakes\SimpleData;

it('works with livewire', function () {
    $class = new class ('') extends Data {
        use WireableData;

        public function __construct(
            public string $name,
        ) {
        }
    };

    $data = $class::fromLivewire(['name' => 'Freek']);

    expect($data)->toEqual(new $class('Freek'));

    expect($data->toLivewire())->toEqual(['name' => 'Freek']);
});

describe('synth tests', function () {
    beforeEach(function () {
        app()->register(LivewireServiceProvider::class);

        Livewire::propertySynthesizer(LivewireDataSynth::class);
        Livewire::propertySynthesizer(LivewireDataCollectionSynth::class);
    });

    it('can initialize a data object', function () {
        livewire(SimpleDataComponent::class, ['name' => 'Hello World'])
            ->assertSet('data.name', 'Hello World');
    });

    it('can set a data object property', function () {
        livewire(SimpleDataComponent::class, ['name' => 'Hello World'])
            ->set('data.name', 'Hello World from Livewire')
            ->assertSet('data.name', 'Hello World from Livewire')
            ->assertSee('Hello World from Livewire');
    });

    it('will not send lazy data to the front when not included', function () {
        livewire(SimpleDataComponent::class, ['name' => Lazy::create(fn () => 'Hello World')])
            ->assertDontSee('Hello World');
    });

    it('is possible to set included lazy data', function () {
        livewire(SimpleDataComponent::class, ['name' => Lazy::create(fn () => 'Hello World'), 'includes' => ['name']])
            ->assertDontSee('Hello World')
            ->set('data.name', 'Hello World from Livewire')
            ->assertSet('data.name', 'Hello World from Livewire')
            ->assertSee('Hello World from Livewire');
    });

    it('can initialize a nested data object', function () {
        livewire(NestedDataComponent::class, ['nested' => new SimpleData('Hello World')])
            ->assertSet('data.simple.string', 'Hello World');
    });

    it('can set a nested data object property', function () {
        livewire(NestedDataComponent::class, ['nested' => new SimpleData('Hello World')])
            ->set('data.simple.string', 'Hello World from Livewire')
            ->assertSet('data.simple.string', 'Hello World from Livewire')
            ->assertSee('Hello World from Livewire');
    });

    it('will not map property names', function () {
        livewire(MappedDataComponent::class)
            ->set('data.string', 'Hello World from Livewire')
            ->assertSet('data.string', 'Hello World from Livewire')
            ->assertSee('Hello World from Livewire');
    });

    it('can use computed properties', function () {
        livewire(ComputedDataComponent::class)
            ->set('data.first_name', 'Ruben')
            ->assertSet('data.first_name', 'Ruben')
            ->assertSet('data.name', ' ') // Computed properties only rerender after constructor calls
            ->assertSee(' ')
            ->set('data.last_name', 'Van Assche')
            ->assertSet('data.last_name', 'Van Assche')
            ->call('save');
    });

    it('can use data collections', function () {
        livewire(DataCollectionComponent::class)
            ->assertSee('a')
            ->assertSee('b')
            ->assertSee('c')
            ->set('collection.0.string', 'Hello World')
            ->assertSet('collection.0.string', 'Hello World')
            ->assertSee('Hello World')
            ->call('save');
    });
});
