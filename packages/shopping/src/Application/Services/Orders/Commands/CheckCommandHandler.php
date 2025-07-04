<?php

namespace RedJasmine\Shopping\Application\Services\Orders\Commands;

use RedJasmine\Shopping\Application\Services\Orders\ShoppingOrderCommandService;
use RedJasmine\Shopping\Application\UserCases\Commands\OrderBuyCommand;
use RedJasmine\Shopping\Domain\Contracts\OrderServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\PromotionServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;
use RedJasmine\Shopping\Domain\Services\OrderDomainService;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;

class CheckCommandHandler extends CommandHandler
{
    protected OrderDomainService $orderDomainService;
    // 构造函数
    // 初始化必要的服务并开始数据库事务
    public function __construct(
        protected ShoppingOrderCommandService $service,
    ) {

        $this->orderDomainService = new OrderDomainService(
            app(ProductServiceInterface::class),
            app(StockServiceInterface::class),
            app(PromotionServiceInterface::class),
            app(OrderServiceInterface::class),
        );

    }

    public function handle(CheckCommand $command)
    {
        return $this->orderDomainService->check($command);
    }

}
