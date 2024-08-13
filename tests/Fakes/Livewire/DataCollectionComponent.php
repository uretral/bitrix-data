<?php

namespace Uretral\BitrixData\Tests\Fakes\Livewire;

use Livewire\Component;
use Uretral\BitrixData\Attributes\DataCollectionOf;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\Tests\Fakes\SimpleData;

class DataCollectionComponent extends Component
{
    #[DataCollectionOf(SimpleData::class)]
    public DataCollection $collection;

    public function mount(): void
    {
        $this->collection = SimpleData::collect([
            'a', 'b', 'c',
        ], DataCollection::class);
    }

    public function save()
    {
    }

    public function render()
    {
        return <<<'BLADE'
        <div>
            @foreach($collection as $item)
                <p>{{ $item->string }}</p>
            @endforeach
            <input type="text" wire:model.live="collection.0.string">
            <p>{{ $collection[0]->string }}</p>
            <button wire:click="save">Save</button>
        </div>
        BLADE;
    }
}
