<?php

namespace RedJasmine\ShoppingCart\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\ShoppingCart\Domain\Repositories\ShoppingCartRepositoryInterface;
use RedJasmine\ShoppingCart\Infrastructure\Repositories\ShoppingCartRepository;

class ShoppingCartApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ShoppingCartRepositoryInterface::class, ShoppingCartRepository::class);
    }
}

<?php

namespace RedJasmine\ShoppingCart\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\ShoppingCart\Domain\Repositories\ShoppingCartRepositoryInterface;
use RedJasmine\ShoppingCart\Infrastructure\Repositories\ShoppingCartRepository;

/**
 * 购物车应用服务提供者
 *
 * 使用统一的仓库接口，简化服务绑定
 */
class ShoppingCartApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 统一仓库接口绑定，支持读写操作
        $this->app->bind(
            ShoppingCartRepositoryInterface::class,
            ShoppingCartRepository::class
        );
    }
}
