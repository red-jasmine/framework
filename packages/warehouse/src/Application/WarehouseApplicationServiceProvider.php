<?php

namespace RedJasmine\Warehouse\Application;

use Illuminate\Support\ServiceProvider;

class WarehouseApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 应用服务已在 WarehousePackageServiceProvider 中注册
    }

    public function boot(): void
    {
    }
}

