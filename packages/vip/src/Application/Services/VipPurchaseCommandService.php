<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Shopping\Application\Services\Commands\ProductBuyCommand;
use RedJasmine\Shopping\Application\Services\ShoppingOrderCommandService;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Vip\Application\Services\Commands\UserPurchaseVipCommand;

class VipPurchaseCommandService extends ApplicationCommandService
{

    public function __construct(
        public ShoppingOrderCommandService $shoppingOrderCommandService
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

        $result = $this->shoppingOrderCommandService->buy($productBuyCommand);
        dd($result);
    }


}