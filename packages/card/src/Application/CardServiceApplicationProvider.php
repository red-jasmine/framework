<?php

namespace RedJasmine\Card\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Card\Domain\Repositories\CardGroupBindProductReadRepositoryInterface;
use RedJasmine\Card\Domain\Repositories\CardGroupBindProductRepositoryInterface;
use RedJasmine\Card\Domain\Repositories\CardGroupReadRepositoryInterface;
use RedJasmine\Card\Domain\Repositories\CardGroupRepositoryInterface;
use RedJasmine\Card\Domain\Repositories\CardReadRepositoryInterface;
use RedJasmine\Card\Domain\Repositories\CardRepositoryInterface;
use RedJasmine\Card\Infrastructure\ReadRepositories\Mysql\CardGroupBindProductReadRepository;
use RedJasmine\Card\Infrastructure\ReadRepositories\Mysql\CardGroupReadRepository;
use RedJasmine\Card\Infrastructure\ReadRepositories\Mysql\CardReadRepository;
use RedJasmine\Card\Infrastructure\Repositories\Eloquent\CardGroupBindProductRepository;
use RedJasmine\Card\Infrastructure\Repositories\Eloquent\CardGroupRepository;
use RedJasmine\Card\Infrastructure\Repositories\Eloquent\CardRepository;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Product\Models\Product;


class CardServiceApplicationProvider extends ServiceProvider
{


    public function register() : void
    {
        $this->app->bind(CardRepositoryInterface::class, CardRepository::class);
        $this->app->bind(CardReadRepositoryInterface::class, CardReadRepository::class);


        $this->app->bind(CardGroupRepositoryInterface::class, CardGroupRepository::class);
        $this->app->bind(CardGroupReadRepositoryInterface::class, CardGroupReadRepository::class);


        $this->app->bind(CardGroupBindProductRepositoryInterface::class, CardGroupBindProductRepository::class);
        $this->app->bind(CardGroupBindProductReadRepositoryInterface::class, CardGroupBindProductReadRepository::class);

        CardGroupBindProduct::morphLabel(Product::class,'商品');

    }

}
