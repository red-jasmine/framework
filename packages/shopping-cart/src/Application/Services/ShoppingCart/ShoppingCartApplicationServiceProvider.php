<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart;

use Illuminate\Support\ServiceProvider;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Repositories\ShoppingCartReadRepositoryInterface;
use RedJasmine\ShoppingCart\Domain\Repositories\ShoppingCartRepositoryInterface;
use RedJasmine\ShoppingCart\Infrastructure\ReadRepositories\Mysql\ShoppingCartReadRepository;
use RedJasmine\ShoppingCart\Infrastructure\Repositories\Eloquent\ShoppingCartRepository;

class ShoppingCartApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 仓库绑定
        $this->app->bind(ShoppingCartRepositoryInterface::class, ShoppingCartRepository::class);
        $this->app->bind(ShoppingCartReadRepositoryInterface::class, ShoppingCartReadRepository::class);

        // 应用服务绑定
        $this->app->bind(ShoppingCartApplicationService::class, ShoppingCartApplicationService::class);
    }
} 