<?php

namespace RedJasmine\Order\Tests\Application\Order\CommandHandlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderHiddenCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Tests\Application\ApplicationTestCase;
use RedJasmine\Order\Tests\Fixtures\Orders\OrderFake;

class OrderHiddenCommandHandlerTest extends ApplicationTestCase
{


    protected function create_order() : Order
    {
        $fake               = new OrderFake();
        $orderCreateCommand = OrderCreateCommand::from($fake->order());

        $order = $this->orderCommandService()->create($orderCreateCommand);
        $this->assertInstanceOf(Order::class, $order);
        return $order;
    }


    /**
     * 创建标准流程订单 SOP
     * 前提条件: 准备订单数据
     * 步骤：
     *  1、对主订单 设置隐藏
     *  2、对主订单 设置显示
     *
     * 预期结果:
     *  1、查询后主订单 删除状态一致
     *  2、查询后主订单 删除状态一致
     *
     * @return void
     */
    public function test_can_hidden() : void
    {
        $order = $this->create_order();

        //步骤：
        // 1、对主订单 设置隐藏
        $orderHiddenCommand = OrderHiddenCommand::from([ 'id' => $order->id ]);

        $this->orderCommandService()->sellerHidden($orderHiddenCommand);
        $this->orderCommandService()->buyerHidden($orderHiddenCommand);

        $order = $this->orderRepository()->find($order->id);

        $this->assertTrue($order->is_seller_delete);
        $this->assertTrue($order->is_buyer_delete);


        // 2、对主订单 设置显示

        $orderHiddenCommand = OrderHiddenCommand::from([ 'id' => $order->id, 'is_hidden' => false ]);

        $this->orderCommandService()->sellerHidden($orderHiddenCommand);
        $this->orderCommandService()->buyerHidden($orderHiddenCommand);

        $order = $this->orderRepository()->find($order->id);

        $this->assertFalse($order->is_seller_delete);
        $this->assertFalse($order->is_buyer_delete);

    }

}
