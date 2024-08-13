---
title: Traits and interfaces
weight: 17
---

Laravel data, is built to be as flexible as possible. This means that you can use it in any way you want.

For example, the `Data` class we've been using throughout these docs is a class implementing a few data interfaces and traits:

```php
use Illuminate\Contracts\Support\Responsable;
use Uretral\BitrixData\Concerns\AppendableData;
use Uretral\BitrixData\Concerns\BaseData;
use Uretral\BitrixData\Concerns\ContextableData;
use Uretral\BitrixData\Concerns\EmptyData;
use Uretral\BitrixData\Concerns\IncludeableData;
use Uretral\BitrixData\Concerns\ResponsableData;
use Uretral\BitrixData\Concerns\TransformableData;
use Uretral\BitrixData\Concerns\ValidateableData;
use Uretral\BitrixData\Concerns\WrappableData;
use Uretral\BitrixData\Contracts\AppendableData as AppendableDataContract;
use Uretral\BitrixData\Contracts\BaseData as BaseDataContract;
use Uretral\BitrixData\Contracts\EmptyData as EmptyDataContract;
use Uretral\BitrixData\Contracts\IncludeableData as IncludeableDataContract;
use Uretral\BitrixData\Contracts\ResponsableData as ResponsableDataContract;
use Uretral\BitrixData\Contracts\TransformableData as TransformableDataContract;
use Uretral\BitrixData\Contracts\ValidateableData as ValidateableDataContract;
use Uretral\BitrixData\Contracts\WrappableData as WrappableDataContract;

abstract class Data implements Responsable, AppendableDataContract, BaseDataContract, TransformableDataContract, IncludeableDataContract, ResponsableDataContract, ValidateableDataContract, WrappableDataContract, EmptyDataContract
{
    use ResponsableData;
    use IncludeableData;
    use AppendableData;
    use ValidateableData;
    use WrappableData;
    use TransformableData;
    use BaseData;
    use EmptyData;
    use ContextableData;
}
```

These traits and interfaces allow you to create your own versions of the base `Data` class, and add your own functionality to it.

An example of such custom base data classes are the `Resource` and `Dto` class.

Each interface (and corresponding trait) provides a piece of functionality:

- **BaseData** provides the base functionality of the data package to create data objects
- **BaseDataCollectable** provides the base functionality of the data package to create data collections
- **ContextableData** provides the functionality to add context for includes and wraps to the data object/collectable
- **IncludeableData** provides the functionality to add includes, excludes, only and except to the data object/collectable
- **TransformableData** provides the functionality to transform the data object/collectable
- **ResponsableData** provides the functionality to return the data object/collectable as a response
- **WrappableData** provides the functionality to wrap the transformed data object/collectable
- **AppendableData** provides the functionality to append data to the transformed data payload
- **EmptyData** provides the functionality to get an empty version of the data object
- **ValidateableData** provides the functionality to validate the data object
- **DeprecatableData** provides the functionality to add deprecated functionality to the data object
