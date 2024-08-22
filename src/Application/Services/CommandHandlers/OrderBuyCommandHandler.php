<?php

namespace RedJasmine\Shopping\Application\Services\CommandHandlers;

use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\Services\StockQueryService;
use RedJasmine\Shopping\Application\Services\OrderCommandService;
use RedJasmine\Shopping\Application\UserCases\Commands\OrderBuyCommand;
use RedJasmine\Shopping\Application\UserCases\Commands\ProductBuyCommand;
use RedJasmine\Shopping\Domain\Orders\OrderDomainService;
use RedJasmine\Support\Application\CommandHandler;

class OrderBuyCommandHandler extends CommandHandler
{
    public function __construct(
        protected ProductCommandService $productCommandService,
        protected ProductQueryService $productQueryService,
        protected StockQueryService $stockQueryService,
        protected StockCommandService $stockCommandService,
        protected OrderCommandService $orderCommandService,
        protected OrderDomainService $orderDomainService,

    ) {
        parent::__construct();
    }


    public function handle(ProductBuyCommand $command)
    {
        $orders = $this->orderDomainService->buy($command);

        return $orders;


    }

}
