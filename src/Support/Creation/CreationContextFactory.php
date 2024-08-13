<?php

namespace Uretral\BitrixData\Support\Creation;

use Illuminate\Container\Container;
use Illuminate\Contracts\Pagination\CursorPaginator as CursorPaginatorContract;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;
use Uretral\BitrixData\Casts\Cast;
use Uretral\BitrixData\CursorPaginatedDataCollection;
use Uretral\BitrixData\DataCollection;
use Uretral\BitrixData\PaginatedDataCollection;
use Uretral\BitrixData\Support\DataContainer;

/**
 * @template TData
 */
class CreationContextFactory
{
    /**
     * @param class-string<TData> $dataClass
     */
    public function __construct(
        public string $dataClass,
        public ValidationStrategy $validationStrategy,
        public bool $mapPropertyNames,
        public bool $disableMagicalCreation,
        public ?array $ignoredMagicalMethods,
        public ?GlobalCastsCollection $casts,
    ) {
    }

    public static function createFromConfig(
        string $dataClass,
        ?array $config = null
    ): self {
        $config ??= [
            /**
             * The package will use this format when working with dates. If this option
             * is an array, it will try to convert from the first format that works,
             * and will serialize dates using the first format from the array.
             */
            'date_format' => DATE_ATOM,

            /**
             * When transforming or casting dates, the following timezone will be used to
             * convert the date to the correct timezone. If set to null no timezone will
             * be passed.
             */
            'date_timezone' => null,

            /**
             * It is possible to enable certain features of the package, these would otherwise
             * be breaking changes, and thus they are disabled by default. In the next major
             * version of the package, these features will be enabled by default.
             */
            'features' => [
                'cast_and_transform_iterables' => false,

                /**
                 * When trying to set a computed property value, the package will throw an exception.
                 * You can disable this behaviour by setting this option to true, which will then just
                 * ignore the value being passed into the computed property and recalculate it.
                 */
                'ignore_exception_when_trying_to_set_computed_property_value' => false,
            ],

            /**
             * Global transformers will take complex types and transform them into simple
             * types.
             */
            'transformers' => [
                DateTimeInterface::class => \Uretral\BitrixData\Transformers\DateTimeInterfaceTransformer::class,
                \Illuminate\Contracts\Support\Arrayable::class => \Uretral\BitrixData\Transformers\ArrayableTransformer::class,
                BackedEnum::class => Uretral\BitrixData\Transformers\EnumTransformer::class,
            ],

            /**
             * Global casts will cast values into complex types when creating a data
             * object from simple types.
             */
            'casts' => [
                DateTimeInterface::class => Uretral\BitrixData\Casts\DateTimeInterfaceCast::class,
                BackedEnum::class => Uretral\BitrixData\Casts\EnumCast::class,
//        Enumerable::class => Uretral\BitrixData\Casts\EnumerableCast::class,
            ],

            /**
             * Rule inferrers can be configured here. They will automatically add
             * validation rules to properties of a data object based upon
             * the type of the property.
             */
            'rule_inferrers' => [
                Uretral\BitrixData\RuleInferrers\SometimesRuleInferrer::class,
                Uretral\BitrixData\RuleInferrers\NullableRuleInferrer::class,
                Uretral\BitrixData\RuleInferrers\RequiredRuleInferrer::class,
                Uretral\BitrixData\RuleInferrers\BuiltInTypesRuleInferrer::class,
                Uretral\BitrixData\RuleInferrers\AttributesRuleInferrer::class,
            ],

            /**
             * Normalizers return an array representation of the payload, or null if
             * it cannot normalize the payload. The normalizers below are used for
             * every data object, unless overridden in a specific data object class.
             */
            'normalizers' => [
                Uretral\BitrixData\Normalizers\ModelNormalizer::class,
                // Uretral\BitrixData\Normalizers\FormRequestNormalizer::class,
                Uretral\BitrixData\Normalizers\ArrayableNormalizer::class,
                Uretral\BitrixData\Normalizers\ObjectNormalizer::class,
                Uretral\BitrixData\Normalizers\ArrayNormalizer::class,
                Uretral\BitrixData\Normalizers\JsonNormalizer::class,
            ],

            /**
             * Data objects can be wrapped into a key like 'data' when used as a resource,
             * this key can be set globally here for all data objects. You can pass in
             * `null` if you want to disable wrapping.
             */
            'wrap' => null,

            /**
             * Adds a specific caster to the Symphony VarDumper component which hides
             * some properties from data objects and collections when being dumped
             * by `dump` or `dd`. Can be 'enabled', 'disabled' or 'development'
             * which will only enable the caster locally.
             */
            'var_dumper_caster_mode' => 'development',

            /**
             * It is possible to skip the PHP reflection analysis of data objects
             * when running in production. This will speed up the package. You
             * can configure where data objects are stored and which cache
             * store should be used.
             *
             * Structures are cached forever as they'll become stale when your
             * application is deployed with changes. You can set a duration
             * in seconds if you want the cache to clear after a certain
             * timeframe.
             */
            'structure_caching' => [
                'enabled' => true,
                'directories' => [app_path('Data')],
                'cache' => [
                    'store' => env('CACHE_STORE', env('CACHE_DRIVER', 'file')),
                    'prefix' => 'laravel-data',
                    'duration' => null,
                ],
                'reflection_discovery' => [
                    'enabled' => true,
                    'base_path' => $_SERVER['DOCUMENT_ROOT'],
                    'root_namespace' => null,
                ],
            ],

            /**
             * A data object can be validated when created using a factory or when calling the from
             * method. By default, only when a request is passed the data is being validated. This
             * behaviour can be changed to always validate or to completely disable validation.
             */
            'validation_strategy' => \Uretral\BitrixData\Support\Creation\ValidationStrategy::OnlyRequests->value,

            /**
             * When using an invalid include, exclude, only or except partial, the package will
             * throw an exception. You can disable this behaviour by setting this option to true.
             */
            'ignore_invalid_partials' => false,

            /**
             * When transforming a nested chain of data objects, the package can end up in an infinite
             * loop when including a recursive relationship. The max transformation depth can be
             * set as a safety measure to prevent this from happening. When set to null, the
             * package will not enforce a maximum depth.
             */
            'max_transformation_depth' => null,

            /**
             * When the maximum transformation depth is reached, the package will throw an exception.
             * You can disable this behaviour by setting this option to true which will return an
             * empty array.
             */
            'throw_when_max_transformation_depth_reached' => true,

            /**
             * When using the `make:data` command, the package will use these settings to generate
             * the data classes. You can override these settings by passing options to the command.
             */
            'commands' => [
                /**
                 * Provides default configuration for the `make:data` command. These settings can be overridden with options
                 * passed directly to the `make:data` command for generating single Data classes, or if not set they will
                 * automatically fall back to these defaults. See `php artisan make:data --help` for more information
                 */
                'make' => [
                    /**
                     * The default namespace for generated Data classes. This exists under the application's root namespace,
                     * so the default 'Data` will end up as '\App\Data', and generated Data classes will be placed in the
                     * app/Data/ folder. Data classes can live anywhere, but this is where `make:data` will put them.
                     */
                    'namespace' => 'Data',

                    /**
                     * This suffix will be appended to all data classes generated by make:data, so that they are less likely
                     * to conflict with other related classes, controllers or models with a similar name without resorting
                     * to adding an alias for the Data object. Set to a blank string (not null) to disable.
                     */
                    'suffix' => 'Data',
                ],
            ],

            /**
             * When using Livewire, the package allows you to enable or disable the synths
             * these synths will automatically handle the data objects and their
             * properties when used in a Livewire component.
             */
            'livewire' => [
                'enable_synths' => false,
            ],
        ];

        return new self(
            dataClass: $dataClass,
            validationStrategy: ValidationStrategy::from($config['validation_strategy']),
            mapPropertyNames: true,
            disableMagicalCreation: false,
            ignoredMagicalMethods: null,
            casts: null,
        );
    }

    public static function createFromCreationContext(
        string $dataClass,
        CreationContext $creationContext,
    ): self {
        return new self(
            dataClass: $dataClass,
            validationStrategy: $creationContext->validationStrategy,
            mapPropertyNames: $creationContext->mapPropertyNames,
            disableMagicalCreation: $creationContext->disableMagicalCreation,
            ignoredMagicalMethods: $creationContext->ignoredMagicalMethods,
            casts: $creationContext->casts,
        );
    }

    public function validationStrategy(ValidationStrategy $validationStrategy): self
    {
        $this->validationStrategy = $validationStrategy;

        return $this;
    }

    public function withoutValidation(): self
    {
        $this->validationStrategy = ValidationStrategy::Disabled;

        return $this;
    }

    public function onlyValidateRequests(): self
    {
        $this->validationStrategy = ValidationStrategy::OnlyRequests;

        return $this;
    }

    public function alwaysValidate(): self
    {
        $this->validationStrategy = ValidationStrategy::Always;

        return $this;
    }

    public function withPropertyNameMapping(bool $withPropertyNameMapping = true): self
    {
        $this->mapPropertyNames = $withPropertyNameMapping;

        return $this;
    }

    public function withoutPropertyNameMapping(bool $withoutPropertyNameMapping = true): self
    {
        $this->mapPropertyNames = ! $withoutPropertyNameMapping;

        return $this;
    }

    public function withoutMagicalCreation(bool $withoutMagicalCreation = true): self
    {
        $this->disableMagicalCreation = $withoutMagicalCreation;

        return $this;
    }

    public function withMagicalCreation(bool $withMagicalCreation = true): self
    {
        $this->disableMagicalCreation = ! $withMagicalCreation;

        return $this;
    }

    public function ignoreMagicalMethod(string ...$methods): self
    {
        $this->ignoredMagicalMethods ??= [];

        array_push($this->ignoredMagicalMethods, ...$methods);

        return $this;
    }

    /**
     * @param string $castable
     * @param Cast|class-string<Cast> $cast
     */
    public function withCast(
        string $castable,
        Cast|string $cast,
    ): self {
        $cast = is_string($cast) ?  Container::getInstance()->make($cast) : $cast;

        if ($this->casts === null) {
            $this->casts = new GlobalCastsCollection();
        }

        $this->casts->add($castable, $cast);

        return $this;
    }

    public function withCastCollection(
        GlobalCastsCollection $casts,
    ): self {
        if ($this->casts === null) {
            $this->casts = $casts;

            return $this;
        }

        $this->casts->merge($casts);

        return $this;
    }

    public function get(): CreationContext
    {
        return new CreationContext(
            dataClass: $this->dataClass,
            mappedProperties: [],
            currentPath: [],
            validationStrategy: $this->validationStrategy,
            mapPropertyNames: $this->mapPropertyNames,
            disableMagicalCreation: $this->disableMagicalCreation,
            ignoredMagicalMethods: $this->ignoredMagicalMethods,
            casts: $this->casts,
        );
    }

    /**
     * @return TData
     */
    public function from(mixed ...$payloads)
    {
        return DataContainer::get()->dataFromSomethingResolver()->execute(
            $this->dataClass,
            $this->get(),
            ...$payloads
        );
    }

    /**
     * @template TCollectKey of array-key
     * @template TCollectValue
     *
     * @param Collection<TCollectKey, TCollectValue>|EloquentCollection<TCollectKey, TCollectValue>|LazyCollection<TCollectKey, TCollectValue>|Enumerable|array<TCollectKey, TCollectValue>|AbstractPaginator|PaginatorContract|AbstractCursorPaginator|CursorPaginatorContract|DataCollection<TCollectKey, TCollectValue> $items
     *
     * @return ($into is 'array' ? array<TCollectKey, TData> : ($into is class-string<EloquentCollection> ? Collection<TCollectKey, TData> : ($into is class-string<Collection> ? Collection<TCollectKey, TData> : ($into is class-string<LazyCollection> ? LazyCollection<TCollectKey, TData> : ($into is class-string<DataCollection> ? DataCollection<TCollectKey, TData> : ($into is class-string<PaginatedDataCollection> ? PaginatedDataCollection<TCollectKey, TData> : ($into is class-string<CursorPaginatedDataCollection> ? CursorPaginatedDataCollection<TCollectKey, TData> : ($items is EloquentCollection ? Collection<TCollectKey, TData> : ($items is Collection ? Collection<TCollectKey, TData> : ($items is LazyCollection ? LazyCollection<TCollectKey, TData> : ($items is Enumerable ? Enumerable<TCollectKey, TData> : ($items is array ? array<TCollectKey, TData> : ($items is AbstractPaginator ? AbstractPaginator : ($items is PaginatorContract ? PaginatorContract : ($items is AbstractCursorPaginator ? AbstractCursorPaginator : ($items is CursorPaginatorContract ? CursorPaginatorContract : ($items is DataCollection ? DataCollection<TCollectKey, TData> : ($items is CursorPaginator ? CursorPaginatedDataCollection<TCollectKey, TData> : ($items is Paginator ? PaginatedDataCollection<TCollectKey, TData> : DataCollection<TCollectKey, TData>)))))))))))))))))))
     */
    public function collect(
        mixed $items,
        ?string $into = null
    ): array|DataCollection|PaginatedDataCollection|CursorPaginatedDataCollection|Enumerable|AbstractPaginator|PaginatorContract|AbstractCursorPaginator|CursorPaginatorContract|LazyCollection|Collection {
        return DataContainer::get()->dataCollectableFromSomethingResolver()->execute(
            $this->dataClass,
            $this->get(),
            $items,
            $into
        );
    }
}
