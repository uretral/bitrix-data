<?php

namespace Uretral\BitrixData;

use Livewire\Livewire;
use Uretral\BitrixData\Commands\DataMakeCommand;
use Uretral\BitrixData\Commands\DataStructuresCacheCommand;
use Uretral\BitrixData\Contracts\BaseData;
use Uretral\BitrixData\Support\Caching\DataStructureCache;
use Uretral\BitrixData\Support\DataConfig;
use Uretral\BitrixData\Support\Livewire\LivewireDataCollectionSynth;
use Uretral\BitrixData\Support\Livewire\LivewireDataSynth;
use Uretral\BitrixData\Support\VarDumper\VarDumperManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelDataServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-data')
            ->hasCommand(DataMakeCommand::class)
            ->hasCommand(DataStructuresCacheCommand::class)
            ->hasConfigFile('data');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(
            DataStructureCache::class,
            fn () => new DataStructureCache(config('data.structure_caching.cache'))
        );

        $this->app->singleton(
            DataConfig::class,
            function () {
                if (config('data.structure_caching.enabled') === false || $this->app->runningUnitTests()) {
                    return DataConfig::createFromConfig(config('data'));
                }

                return $this->app->make(DataStructureCache::class)->getConfig() ?? DataConfig::createFromConfig(config('data'));
            }
        );

        $this->app->beforeResolving(BaseData::class, function ($class, $parameters, $app) {
            if ($app->has($class)) {
                return;
            }

            $app->bind(
                $class,
                fn ($container) => $class::from($container['request'])
            );
        });

        if(config('data.livewire.enable_synths') && class_exists(Livewire::class)) {
            $this->registerLivewireSynths();
        }
    }

    protected function registerLivewireSynths(): void
    {
        Livewire::propertySynthesizer(LivewireDataSynth::class);
        Livewire::propertySynthesizer(LivewireDataCollectionSynth::class);
    }

    public function packageBooted(): void
    {
        $enableVarDumperCaster = match (config('data.var_dumper_caster_mode')) {
            'enabled' => true,
            'development' => $this->app->environment('local', 'testing'),
            default => false,
        };

        if ($enableVarDumperCaster) {
            (new VarDumperManager())->initialize();
        }
    }
}
