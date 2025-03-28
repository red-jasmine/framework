<?php

namespace RedJasmine\Product\Application\Stock\Services;

use RedJasmine\Product\Application\Stock\Services\Commands\BulkStockCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockAddCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockConfirmCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockLockCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockResetCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockSubCommandHandler;
use RedJasmine\Product\Application\Stock\Services\Commands\StockUnlockCommandHandler;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuRepositoryInterface;
use RedJasmine\Product\Domain\Stock\StockDomainService;
use RedJasmine\Support\Application\ApplicationCommandService;


class StockCommandService extends ApplicationCommandService
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.stock.command';


    protected static $macros = [
        'bulk'    => BulkStockCommandHandler::class,
        'reset'   => StockResetCommandHandler::class,
        'add'     => StockAddCommandHandler::class,
        'sub'     => StockSubCommandHandler::class,
        'lock'    => StockLockCommandHandler::class,
        'unlock'  => StockUnlockCommandHandler::class,
        'confirm' => StockConfirmCommandHandler::class,
    ];

    public function __construct(
        protected ProductSkuRepositoryInterface $repository,
        protected StockDomainService            $domainService
    )
    {

    }


}
