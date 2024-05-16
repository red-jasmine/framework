<?php

namespace RedJasmine\Product\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Product\Domain\Brand\Repositories\BrandReadRepositoryInterface;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryReadRepositoryInterface;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Product\Domain\Category\Repositories\ProductSellerCategoryReadRepositoryInterface;
use RedJasmine\Product\Domain\Category\Repositories\ProductSellerCategoryRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupReadRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyReadRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueReadRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesReadRepositoryInterface;
use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesRepositoryInterface;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\BrandReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductCategoryReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductPropertyGroupReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductPropertyReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductSellerCategoryReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductSeriesReadRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\BrandRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductCategoryRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductPropertyGroupRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductPropertyRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductPropertyValueRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductSellerCategoryRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductSeriesRepository;

class ProductApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(BrandReadRepositoryInterface::class, BrandReadRepository::class);

        $this->app->bind(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
        $this->app->bind(ProductCategoryReadRepositoryInterface::class, ProductCategoryReadRepository::class);


        $this->app->bind(ProductSellerCategoryRepositoryInterface::class, ProductSellerCategoryRepository::class);
        $this->app->bind(ProductSellerCategoryReadRepositoryInterface::class, ProductSellerCategoryReadRepository::class);

        $this->app->bind(ProductPropertyGroupRepositoryInterface::class, ProductPropertyGroupRepository::class);
        $this->app->bind(ProductPropertyGroupReadRepositoryInterface::class, ProductPropertyGroupReadRepository::class);

        $this->app->bind(ProductPropertyRepositoryInterface::class, ProductPropertyRepository::class);
        $this->app->bind(ProductPropertyReadRepositoryInterface::class, ProductPropertyReadRepository::class);

        $this->app->bind(ProductPropertyValueRepositoryInterface::class, ProductPropertyValueRepository::class);
        $this->app->bind(ProductPropertyValueReadRepositoryInterface::class, ProductPropertyValueRepository::class);


        $this->app->bind(ProductSeriesRepositoryInterface::class, ProductSeriesRepository::class);
        $this->app->bind(ProductSeriesReadRepositoryInterface::class, ProductSeriesReadRepository::class);


    }

    public function boot() : void
    {
    }
}
