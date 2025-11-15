<?php

namespace RedJasmine\Product\Application\Price\Services;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Product\Application\Price\Services\Commands\ProductPriceCreateCommand;
use RedJasmine\Product\Application\Price\Services\Commands\ProductPriceCreateCommandHandler;
use RedJasmine\Product\Application\Price\Services\Commands\ProductPriceDeleteCommand;
use RedJasmine\Product\Application\Price\Services\Commands\ProductPriceDeleteCommandHandler;
use RedJasmine\Product\Application\Price\Services\Commands\ProductPriceUpdateCommand;
use RedJasmine\Product\Application\Price\Services\Commands\ProductPriceUpdateCommandHandler;
use RedJasmine\Product\Application\Price\Services\Queries\GetProductPriceQueryHandler;
use RedJasmine\Product\Application\Price\Services\Queries\ProductPriceListQueryHandler;
use RedJasmine\Product\Domain\Price\Data\ProductPriceData;
use RedJasmine\Product\Domain\Price\Models\ProductPrice;
use RedJasmine\Product\Domain\Price\Models\ProductVariantPrice;
use RedJasmine\Product\Domain\Price\ProductPriceDomainService;
use RedJasmine\Product\Domain\Price\Repositories\ProductPriceRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

/**
 * @method ProductPrice find(FindQuery $query) 查找商品级价格汇总
 * @method ProductPrice create(ProductPriceCreateCommand $command) 创建/更新变体价格并返回商品价格汇总
 * @method ProductPrice update(ProductPriceUpdateCommand $command) 更新变体价格并返回商品价格汇总
 * @method void delete(ProductPriceDeleteCommand $command) 删除变体价格（会自动更新商品价格汇总）
 */
class PriceApplicationService extends ApplicationService
{
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.price';

    protected static string $modelClass = ProductPrice::class;

    protected static $macros = [
        'create'   => ProductPriceCreateCommandHandler::class,
        'update'   => ProductPriceUpdateCommandHandler::class,
        'delete'   => ProductPriceDeleteCommandHandler::class,
        'paginate' => ProductPriceListQueryHandler::class,
        'getPrice' => GetProductPriceQueryHandler::class,
    ];

    public function __construct(
        public ProductPriceRepositoryInterface $repository,
        protected ProductPriceDomainService $domainService
    ) {
    }

    /**
     * 获取商品变体价格（便捷方法）
     *
     * @param  ProductPriceData  $data
     *
     * @return ProductVariantPrice|null
     */
    public function getPrice(ProductPriceData $data) : ?ProductVariantPrice
    {
        return $this->domainService->getPrice($data);
    }

    /**
     * 批量获取商品级别价格汇总（便捷方法，用于商品列表）
     *
     * @param  Collection  $products  商品集合
     * @param  string  $market  市场
     * @param  string  $store  门店
     * @param  string  $userLevel  用户等级
     *
     * @return array<int, ProductPrice|null> key为 product_id
     */
    public function getBatchProductPrices(
        Collection $products,
        string $market = '*',
        string $store = '*',
        string $userLevel = '*'
    ) : array {
        return $this->domainService->getBatchProductPrices($products, $market, $store, $userLevel);
    }

    /**
     * 批量获取商品变体价格（便捷方法，用于商品详情）
     *
     * @param  Collection  $products  商品集合
     * @param  string  $market  市场
     * @param  string  $store  门店
     * @param  string  $userLevel  用户等级
     * @param  bool  $useDefaultVariant  是否只查询默认变体
     *
     * @return array<string, ProductVariantPrice|null>
     */
    public function getBatchPrices(
        Collection $products,
        string $market = '*',
        string $store = '*',
        string $userLevel = '*',
        bool $useDefaultVariant = true
    ) : array {
        return $this->domainService->getBatchPrices($products, $market, $store, $userLevel, $useDefaultVariant);
    }
}

