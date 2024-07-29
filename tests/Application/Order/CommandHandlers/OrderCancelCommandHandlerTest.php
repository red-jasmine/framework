<?php

namespace RedJasmine\Order\Tests\Application\Order\CommandHandlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderCancelCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Tests\Application\ApplicationTestCase;
use RedJasmine\Order\Tests\Fixtures\Orders\OrderFake;

class OrderCancelCommandHandlerTest extends ApplicationTestCase
{


    /**
     *  创建标准流程订单 SOP
     * 前提条件: 准备订单数据
     * 步骤：
     *      1、创建订单
     *      2、取消订单
     *      3、
     * 预期结果: TODO
     *       1、有返回结果
     *
     *
     * @return void
     */
    public function test_can_cancel_sop_not_paid_order() : void
    {

        $fake               = new OrderFake();
        $orderCreateCommand = OrderCreateCommand::from($fake->order());

        $order = $this->orderCommandService()->create($orderCreateCommand);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(PaymentStatusEnum::WAIT_PAY->value, $order->payment_status->value);

        // 2、取消订单

        $orderCancelCommand = OrderCancelCommand::from([ 'id' => $order->id, 'reason' => '不想要了' ]);
        $this->orderCommandService()->cancel($orderCancelCommand);

        $order = $this->orderRepository()->find($order->id);
        $this->assertEquals(OrderStatusEnum::CANCEL->value, $order->order_status->value, '订单状态不一致');
        $this->assertNotEmpty($order->close_time, '订单关闭时间没有设置');
        $this->assertEquals($orderCancelCommand->cancelReason, $order->cancel_reason, '取消原因');
        foreach ($order->products as $product) {
            $this->assertEquals(OrderStatusEnum::CANCEL->value, $product->order_status->value,'子商品单状态');
            $this->assertNotEmpty($product->close_time, '子商品单关闭时间没有设置');
        }
    }

}
