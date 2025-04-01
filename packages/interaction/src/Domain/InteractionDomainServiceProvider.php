<?php

namespace RedJasmine\Interaction\Domain;

use Illuminate\Support\ServiceProvider;

class InteractionDomainServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->singleton(InteractionManager::class, function () {
            $config = config('red-jasmine-interaction.strategies');
            return new InteractionManager($config);
        });
    }

    public function boot() : void
    {
    }
}
