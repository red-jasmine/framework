<?php

namespace RedJasmine\Shopping\Application\Services\CommandHandlers;

use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Shopping\Application\Services\OrderCommandService;
use RedJasmine\Shopping\Application\UserCases\Commands\OrderBuyCommand;
use RedJasmine\Support\Application\CommandHandler;

class OrderBuyCommandHandler extends CommandHandler
{
    public function __construct(
        protected ProductCommandService $productCommandService,
        protected OrderCommandService   $orderCommandService,
    )
    {
        parent::__construct();
    }


    public function handle(OrderBuyCommand $command)
    {

        // 按卖家拆单
        // 商品验证

        // 计算邮费

        // 计算优惠

        // 创建订单

        // 合单支付

        $this->orderCommandService->create($command);
    }

}
