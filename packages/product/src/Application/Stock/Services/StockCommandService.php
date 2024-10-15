<?php

namespace RedJasmine\Product\Application\Stock\Services;

use RedJasmine\Product\Application\Stock\Services\CommandHandlers\BulkStockCommandHandler;
use RedJasmine\Product\Application\Stock\Services\CommandHandlers\StockAddCommandHandler;
use RedJasmine\Product\Application\Stock\Services\CommandHandlers\StockConfirmCommandHandler;
use RedJasmine\Product\Application\Stock\Services\CommandHandlers\StockLockCommandHandler;
use RedJasmine\Product\Application\Stock\Services\CommandHandlers\StockResetCommandHandler;
use RedJasmine\Product\Application\Stock\Services\CommandHandlers\StockSubCommandHandler;
use RedJasmine\Product\Application\Stock\Services\CommandHandlers\StockUnlockCommandHandler;
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
