<?php

namespace RedJasmine\Article\Domain;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Article\Domain\Interaction\ArticleInteraction;
use RedJasmine\Interaction\Domain\Facades\InteractionResource;

class ArticleDomainServiceProvider extends ServiceProvider
{
    public function register() : void
    {

    }

    public function boot() : void
    {
        InteractionResource::extend('article', function ($config) {

            return new ArticleInteraction();
        });
    }
}
