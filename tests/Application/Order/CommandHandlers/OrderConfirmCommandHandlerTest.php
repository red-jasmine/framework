<?php

namespace RedJasmine\Order\Tests\Application\Order\CommandHandlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderConfirmCommand;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class OrderConfirmCommandHandlerTest extends OrderCommandServiceTestCase
{


    /**
     *  订单可以确认
     * 前提条件: 订单支付、订单全部发货
     * 步骤：
     *  1、
     *  2、
     *  3、
     * 预期结果:
     *  1、订单已确认、子商品单已确认
     *  2、
     * @return void
     */
    public function test_can_order_confirm() : void
    {

        $order = $this->orderPaidAndShipping();

        $command = OrderConfirmCommand::from([ 'id' => $order->id ]);

        $this->orderCommandService()->confirm($command);

        $order = $this->orderQueryService()->findById(FindQuery::make($command->id));


        $this->assertEquals(OrderStatusEnum::FINISHED->value, $order->order_status->value);
        foreach ($order->products as $product){
            $this->assertEquals(OrderStatusEnum::FINISHED->value, $product->order_status->value);
        }

    }

}
