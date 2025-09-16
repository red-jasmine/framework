<?php

namespace RedJasmine\Address\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Address\Domain\Repositories\AddressRepositoryInterface;
use RedJasmine\Address\Infrastructure\Repositories\AddressRepository;

/**
 * 地址应用服务提供者
 *
 * 使用统一的Repository实现，简化了依赖注册
 */
class AddressApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 注册统一的仓库实现，同时支持读写操作
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
    }
}
