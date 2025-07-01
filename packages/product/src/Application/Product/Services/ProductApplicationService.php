<?php

namespace RedJasmine\Product\Application\Product\Services;

use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommandHandler;
use RedJasmine\Product\Application\Product\Services\Commands\ProductDeleteCommandHandler;
use RedJasmine\Product\Application\Product\Services\Commands\ProductSetStatusCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductSetStatusCommandHandler;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommandHandler;
use RedJasmine\Product\Application\Product\Services\Queries\GetProductPurchaseQuery;
use RedJasmine\Product\Application\Product\Services\Queries\GetProductPriceQueryHandler;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Repositories\ProductReadRepositoryInterface;
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
 * @method  getProductPrice(GetProductPurchaseQuery $query)
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
        public ProductRepositoryInterface $repository,
        public ProductReadRepositoryInterface $readRepository

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
    ];


}
