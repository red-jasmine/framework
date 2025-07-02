<?php

namespace RedJasmine\Shopping\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Shopping\Domain\Repositories\ShoppingCartReadRepositoryInterface;
use RedJasmine\Shopping\Domain\Repositories\ShoppingCartRepositoryInterface;
use RedJasmine\Shopping\Infrastructure\ReadRepositories\Mysql\ShoppingCartReadRepository;
use RedJasmine\Shopping\Infrastructure\Repositories\Eloquent\ShoppingCartRepository;

class ShoppingCartApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 仓库绑定
        $this->app->bind(ShoppingCartRepositoryInterface::class, ShoppingCartRepository::class);
        $this->app->bind(ShoppingCartReadRepositoryInterface::class, ShoppingCartReadRepository::class);

    }
} 