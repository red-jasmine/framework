<?php

namespace RedJasmine\Product\Application\Stock\Services;

use RedJasmine\Product\Application\Stock\Services\Commands\BulkStockCommand;
use RedJasmine\Product\Application\Stock\Services\Commands\BulkStockCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockAddCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockCommand;
use RedJasmine\Product\Application\Stock\Services\Commands\StockConfirmCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockLockCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockResetCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockSubCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockUnlockCommandHandler;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuReadRepositoryInterface;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuRepositoryInterface;
use RedJasmine\Product\Domain\Stock\StockDomainService;
use RedJasmine\Support\Application\ApplicationService;


/**
 * @method bulk(BulkStockCommand $command)
 * @method sub(StockCommand $command)
 * @method reset(StockCommand $command)
 * @method add(StockCommand $command)
 * @method lock(StockCommand $command)
 * @method unlock(StockCommand $command)
 * @method confirm(StockCommand $command)
 */
class StockApplicationService extends ApplicationService
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.stock';


    protected static $macros = [
        'create'  => null,
        'update'  => null,
        'delete'  => null,
        'bulk'    => BulkStockCommandHandler::class,
        'reset'   => StockResetCommandHandler::class,
        'add'     => StockAddCommandHandler::class,
        'sub'     => StockSubCommandHandler::class,
        'lock'    => StockLockCommandHandler::class,
        'unlock'  => StockUnlockCommandHandler::class,
        'confirm' => StockConfirmCommandHandler::class,
    ];

    public function __construct(
        public ProductSkuRepositoryInterface $repository,
        public ProductSkuReadRepositoryInterface $readRepository,
        public StockDomainService $domainService
    ) {

    }


}
