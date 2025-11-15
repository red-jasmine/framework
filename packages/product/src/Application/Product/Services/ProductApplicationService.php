<?php

namespace RedJasmine\Product\Application\Product\Services;

use RedJasmine\Money;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductAmountInfo;
use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommandHandler;
use RedJasmine\Product\Application\Product\Services\Commands\ProductDeleteCommandHandler;
use RedJasmine\Product\Application\Product\Services\Commands\ProductSetStatusCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductSetStatusCommandHandler;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommandHandler;
use RedJasmine\Product\Application\Product\Services\Queries\GetProductPriceQueryHandler;
use RedJasmine\Product\Application\Product\Services\Queries\GetProductPurchaseQuery;
use RedJasmine\Product\Application\Product\Services\Queries\UserProductListQueryHandler;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;


/**
 * @method Product find(FindQuery $query)
 * @see ProductCreateCommandHandler::handle()
 * @method Product create(ProductCreateCommand $command)
 * @see ProductUpdateCommandHandler::handle()
 * @method void update(ProductUpdateCommand $command)
 * @method void setStatus(ProductSetStatusCommand $command)
 * @method ProductAmountInfo  getProductPrice(GetProductPurchaseQuery $query)
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator userProductList(\RedJasmine\Product\Application\Product\Services\Queries\UserProductListQuery $query)
 */
class ProductApplicationService extends ApplicationService
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.product';


    protected static string $modelClass = Product::class;


    public function __construct(
        public ProductRepositoryInterface $repository
    ) {
    }

    public function getDefaultModelWithInfo() : array
    {
        return ['extension', 'tags'];
    }

    protected static $macros = [
        'create'          => ProductCreateCommandHandler::class,
        'update'          => ProductUpdateCommandHandler::class,
        'delete'          => ProductDeleteCommandHandler::class,
        'setStatus'       => ProductSetStatusCommandHandler::class,

        // 查询器
        'getProductPrice' => GetProductPriceQueryHandler::class,
        'userProductList' => UserProductListQueryHandler::class,
    ];

    /**
     * 获取货币
     * @return array
     */
    public static function getCurrencies() : array
    {
        $currencies      = Money::getCurrencies();
        $currenciesCodes = [];
        $allowCurrencies = config('red-jasmine-product.currencies', []);

        foreach ($currencies->getIterator() as $currency) {
            if (in_array($currency->getCode(), $allowCurrencies)) {
                $currenciesCodes[$currency->getCode()] = $currencies->getSymbol($currency).' '.trans('money::currencies.'.$currency->getCode()).'('.$currency->getCode().')';
            }
        }
        return $currenciesCodes;
    }

}
