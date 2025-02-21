<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\Services\Orders\OrderCommandService;
use RedJasmine\Shopping\Application\Services\Commands\ProductBuyCommand;
use RedJasmine\Shopping\Application\Services\ShoppingOrderCommandService;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Vip\Application\Services\Commands\UserPurchaseVipCommand;

class VipPurchaseCommandService extends ApplicationCommandService
{

    public function __construct(
        public ShoppingOrderCommandService $shoppingOrderCommandService,
        public OrderCommandService $orderCommandService,
    ) {
    }


    public function buy(UserPurchaseVipCommand $command)
    {
        // 下单
        // 发起支付
        // 返回支付中心的 支付信息


        $productBuyCommand = ProductBuyCommand::from([
            'buyer'    => $command->owner,
            'products' => [
                [
                    'product_id' => $command->id,
                    'sku_id'     => $command->id,
                    'quantity'   => $command->quantity,
                ],
            ],
        ]);

        $orders = $this->shoppingOrderCommandService->buy($productBuyCommand);

        $orderPayingCommand = OrderPayingCommand::from([
            'id' => $orders->first()->id,
        ]);

        // 订单发起支付
        $orderPayingResult = $this->orderCommandService->paying($orderPayingCommand);

        // 需要一个组合的组件 把 订单 和 支付组件联系在一起 TODO

        dd($orderPayingResult);
    }


}