<?php

namespace RedJasmine\Warehouse;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Warehouse\Application\WarehouseApplicationServiceProvider;
use RedJasmine\Warehouse\Domain\Repositories\WarehouseRepositoryInterface;
use RedJasmine\Warehouse\Domain\Transformer\WarehouseTransformer;
use RedJasmine\Warehouse\Infrastructure\Repositories\WarehouseRepository;

class WarehousePackageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 注册仓库接口
        $this->app->bind(WarehouseRepositoryInterface::class, WarehouseRepository::class);
        
        // 注册转换器
        $this->app->singleton(WarehouseTransformer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'red-jasmine-warehouse');
    }
}

