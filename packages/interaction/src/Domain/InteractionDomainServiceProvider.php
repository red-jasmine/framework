<?php

namespace RedJasmine\Interaction\Domain;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Interaction\Domain\Resources\InteractionResourceManager;
use RedJasmine\Interaction\Domain\Types\InteractionTypeManager;

class InteractionDomainServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->singleton(InteractionResourceManager::class, function () {
            $config = config('red-jasmine-interaction.resources', []);
            return new InteractionResourceManager($config);
        });

        $this->app->singleton(InteractionTypeManager::class, function () {
            $config = config('red-jasmine-interaction.types', []);
            return new InteractionTypeManager($config);
        });
    }

    public function boot() : void
    {

    }
}
