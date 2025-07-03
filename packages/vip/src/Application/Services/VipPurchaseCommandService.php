<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Shopping\Application\Services\Orders\Commands\OrderPayCommand;
use RedJasmine\Shopping\Application\Services\Orders\Commands\ProductBuyCommand;
use RedJasmine\Shopping\Application\Services\Orders\ShoppingOrderCommandService;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Vip\Application\Services\Commands\UserPurchaseVipCommand;

class VipPurchaseCommandService extends ApplicationCommandService
{

    public function __construct(
        public ShoppingOrderCommandService $shoppingOrderCommandService,
        public OrderApplicationService $orderCommandService,
    ) {
    }


    public function buy(UserPurchaseVipCommand $command)
    {
        // 下单
        // 发起支付
        // 返回支付中心的 支付信息


        $productBuyCommand = ProductBuyCommand::from([
            'buyer'    => $command->owner,
            'title'    => '购买会员',
            'products' => [
                [
                    'product_id' => $command->id,
                    'sku_id'     => $command->id,
                    'quantity'   => $command->quantity,
                ],
            ],
        ]);

        // 下单
        $orders = $this->shoppingOrderCommandService->buy($productBuyCommand);

        $orderPayCommand = OrderPayCommand::from([
            'id' => $orders->first()->id,
        ]);


        // 调用支付
        // 订单发起支付
        return $this->shoppingOrderCommandService->pay($orderPayCommand);
    }


}