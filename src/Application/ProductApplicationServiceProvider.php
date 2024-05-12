<?php

namespace RedJasmine\Product\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryReadRepositoryInterface;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Product\Domain\Brand\Repositories\BrandReadRepositoryInterface;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\BrandReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductCategoryReadRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\BrandRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductCategoryRepository;

class ProductApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(BrandReadRepositoryInterface::class, BrandReadRepository::class);

        $this->app->bind(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
        $this->app->bind(ProductCategoryReadRepositoryInterface::class, ProductCategoryReadRepository::class);


    }

    public function boot() : void
    {
    }
}
