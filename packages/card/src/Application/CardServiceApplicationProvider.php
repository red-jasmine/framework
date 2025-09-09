<?php

namespace RedJasmine\Card\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Card\Domain\Repositories\CardGroupBindProductRepositoryInterface;
use RedJasmine\Card\Domain\Repositories\CardGroupRepositoryInterface;
use RedJasmine\Card\Domain\Repositories\CardRepositoryInterface;
use RedJasmine\Card\Infrastructure\Repositories\CardGroupBindProductRepository;
use RedJasmine\Card\Infrastructure\Repositories\CardGroupRepository;
use RedJasmine\Card\Infrastructure\Repositories\CardRepository;
use RedJasmine\Product\Domain\Product\Models\Product;

/**
 * 卡密服务应用提供者
 *
 * 使用统一的仓库接口，支持读写操作
 */
class CardServiceApplicationProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind(CardRepositoryInterface::class, CardRepository::class);
        $this->app->bind(CardGroupRepositoryInterface::class, CardGroupRepository::class);
        $this->app->bind(CardGroupBindProductRepositoryInterface::class, CardGroupBindProductRepository::class);

        CardGroupBindProduct::morphLabel(Product::class,'商品');
    }
}
