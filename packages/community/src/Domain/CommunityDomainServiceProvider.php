<?php

namespace RedJasmine\Community\Domain;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Community\Domain\Interaction\CommunityInteraction;
use RedJasmine\Interaction\Domain\Facades\InteractionResource;

class CommunityDomainServiceProvider extends ServiceProvider
{
    public function register() : void
    {

    }

    public function boot() : void
    {
        InteractionResource::extend('topic', function ($config) {

            return new CommunityInteraction();
        });
    }
}
