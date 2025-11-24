<?php

namespace RedJasmine\Product\Application\Stock\Services;

use RedJasmine\Product\Application\Stock\Services\Commands\BulkStockCommand;
use RedJasmine\Product\Application\Stock\Services\Commands\BulkStockCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockAddCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockCommand;
use RedJasmine\Product\Application\Stock\Services\Commands\StockResetCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockSubCommandHandler;
use RedJasmine\Product\Domain\Stock\Repositories\ProductStockLogRepositoryInterface;
use RedJasmine\Product\Domain\Stock\Repositories\ProductStockRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;


/**
 * @method bulk(BulkStockCommand $command)
 * @method sub(StockCommand $command)
 * @method reset(StockCommand $command)
 * @method add(StockCommand $command)
 *
 * @method lock(StockCommand $command)
 * @method unlock(StockCommand $command)
 * @method reserve(StockCommand $command)
 * @method release(StockCommand $command)
 * @method deduct(StockCommand $command)
 */
class StockApplicationService extends ApplicationService
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.stock';


    protected static $macros = [
        'create' => null,
        'update' => null,
        'delete' => null,
        'bulk'   => BulkStockCommandHandler::class,
        'reset'  => StockResetCommandHandler::class,
        'add'    => StockAddCommandHandler::class,
        'sub'    => StockSubCommandHandler::class,


    ];

    public function __construct(
        public ProductStockRepositoryInterface $repository,
        public ProductStockLogRepositoryInterface $logRepository,
    ) {

    }


}
