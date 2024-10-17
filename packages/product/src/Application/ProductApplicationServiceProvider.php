<?php

namespace RedJasmine\Product\Application;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Repositories\BrandReadRepositoryInterface;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryReadRepositoryInterface;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupReadRepositoryInterface;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupRepositoryInterface;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Repositories\ProductReadRepositoryInterface;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupReadRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyReadRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueReadRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesReadRepositoryInterface;
use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesRepositoryInterface;
use RedJasmine\Product\Domain\Service\Repositories\ProductServiceReadRepositoryInterface;
use RedJasmine\Product\Domain\Service\Repositories\ProductServiceRepositoryInterface;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuReadRepositoryInterface;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuRepositoryInterface;
use RedJasmine\Product\Domain\Stock\Repositories\ProductStockLogReadRepositoryInterface;
use RedJasmine\Product\Domain\Tag\Repositories\ProductTagReadRepositoryInterface;
use RedJasmine\Product\Domain\Tag\Repositories\ProductTagRepositoryInterface;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\BrandReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductCategoryReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductPropertyGroupReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductPropertyReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductPropertyValueReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductGroupReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductSeriesReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductServiceReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductSkuReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductStockLogReadRepository;
use RedJasmine\Product\Infrastructure\ReadRepositories\Mysql\ProductTagReadRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\BrandRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductCategoryRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductPropertyGroupRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductPropertyRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductPropertyValueRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductGroupRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductSeriesRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductServiceRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductSkuRepository;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductTagRepository;

class ProductApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(BrandReadRepositoryInterface::class, BrandReadRepository::class);

        $this->app->bind(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
        $this->app->bind(ProductCategoryReadRepositoryInterface::class, ProductCategoryReadRepository::class);


        $this->app->bind(ProductGroupRepositoryInterface::class, ProductGroupRepository::class);
        $this->app->bind(ProductGroupReadRepositoryInterface::class, ProductGroupReadRepository::class);

        $this->app->bind(ProductPropertyGroupRepositoryInterface::class, ProductPropertyGroupRepository::class);
        $this->app->bind(ProductPropertyGroupReadRepositoryInterface::class, ProductPropertyGroupReadRepository::class);

        $this->app->bind(ProductPropertyRepositoryInterface::class, ProductPropertyRepository::class);
        $this->app->bind(ProductPropertyReadRepositoryInterface::class, ProductPropertyReadRepository::class);

        $this->app->bind(ProductPropertyValueRepositoryInterface::class, ProductPropertyValueRepository::class);
        $this->app->bind(ProductPropertyValueReadRepositoryInterface::class, ProductPropertyValueReadRepository::class);


        $this->app->bind(ProductSeriesRepositoryInterface::class, ProductSeriesRepository::class);
        $this->app->bind(ProductSeriesReadRepositoryInterface::class, ProductSeriesReadRepository::class);

        $this->app->bind(ProductTagRepositoryInterface::class, ProductTagRepository::class);
        $this->app->bind(ProductTagReadRepositoryInterface::class, ProductTagReadRepository::class);


        $this->app->bind(ProductServiceRepositoryInterface::class, ProductServiceRepository::class);
        $this->app->bind(ProductServiceReadRepositoryInterface::class, ProductServiceReadRepository::class);


        $this->app->bind(ProductReadRepositoryInterface::class, ProductReadRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);


        // 库存
        $this->app->bind(ProductSkuRepositoryInterface::class, ProductSkuRepository::class);
        $this->app->bind(ProductSkuReadRepositoryInterface::class, ProductSkuReadRepository::class);
        $this->app->bind(ProductStockLogReadRepositoryInterface::class, ProductStockLogReadRepository::class);


        Relation::enforceMorphMap([
                                      'product' => Product::class,
                                      'brand'   => Brand::class,
                                  ]);

    }

    public function boot() : void
    {
    }
}
