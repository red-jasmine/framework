<?php

namespace RedJasmine\Product\Application;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupRepositoryInterface;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesRepositoryInterface;
use RedJasmine\Product\Domain\Service\Repositories\ProductServiceRepositoryInterface;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuRepositoryInterface;
use RedJasmine\Product\Domain\Stock\Repositories\ProductStockLogRepositoryInterface;
use RedJasmine\Product\Domain\Tag\Repositories\ProductTagRepositoryInterface;
use RedJasmine\Product\Infrastructure\Repositories\BrandRepository;
use RedJasmine\Product\Infrastructure\Repositories\ProductCategoryRepository;
use RedJasmine\Product\Infrastructure\Repositories\ProductPropertyGroupRepository;
use RedJasmine\Product\Infrastructure\Repositories\ProductPropertyRepository;
use RedJasmine\Product\Infrastructure\Repositories\ProductPropertyValueRepository;
use RedJasmine\Product\Infrastructure\Repositories\ProductRepository;
use RedJasmine\Product\Infrastructure\Repositories\ProductGroupRepository;
use RedJasmine\Product\Infrastructure\Repositories\ProductSeriesRepository;
use RedJasmine\Product\Infrastructure\Repositories\ProductServiceRepository;
use RedJasmine\Product\Infrastructure\Repositories\ProductSkuRepository;
use RedJasmine\Product\Infrastructure\Repositories\ProductStockLogRepository;
use RedJasmine\Product\Infrastructure\Repositories\ProductTagRepository;

class ProductApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
        $this->app->bind(ProductGroupRepositoryInterface::class, ProductGroupRepository::class);
        $this->app->bind(ProductPropertyGroupRepositoryInterface::class, ProductPropertyGroupRepository::class);
        $this->app->bind(ProductPropertyRepositoryInterface::class, ProductPropertyRepository::class);
        $this->app->bind(ProductPropertyValueRepositoryInterface::class, ProductPropertyValueRepository::class);
        $this->app->bind(ProductSeriesRepositoryInterface::class, ProductSeriesRepository::class);
        $this->app->bind(ProductTagRepositoryInterface::class, ProductTagRepository::class);
        $this->app->bind(ProductServiceRepositoryInterface::class, ProductServiceRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductSkuRepositoryInterface::class, ProductSkuRepository::class);
        $this->app->bind(ProductStockLogRepositoryInterface::class, ProductStockLogRepository::class);

        // Relation::enforceMorphMap([
        //                               'product' => Product::class,
        //                               'brand'   => Brand::class,
        //                           ]);

    }

    public function boot() : void
    {
    }
}
