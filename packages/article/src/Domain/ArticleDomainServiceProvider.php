<?php

namespace RedJasmine\Article\Domain;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Article\Domain\Interaction\ArticleInteractionStrategy;
use RedJasmine\Interaction\Domain\Facades\Interaction;

class ArticleDomainServiceProvider extends ServiceProvider
{
    public function register() : void
    {

    }

    public function boot() : void
    {
        Interaction::extend('article', function ($config) {
            return new ArticleInteractionStrategy();
        });
    }
}
