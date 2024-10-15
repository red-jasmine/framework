<?php

namespace RedJasmine\Order\Tests\Application\Order\CommandHandlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Tests\Application\ApplicationTestCase;
use RedJasmine\Order\Tests\Fixtures\Orders\OrderFake;

class OrderPaidCommandHandlerTest extends ApplicationTestCase
{


    /**
     * 能支付成功
     * 前提条件: 准备订单数据
     * 步骤：
     *  1、创建订单
     *  2、调用支付
     *  3、设置支付成功
     * 预期结果:
     *  1、订单支付状态成功、付款金额为应付金额
     *  2、支付单支付状态成功、
     * @return void
     */
    public function test_can_order_paid() : void
    {

        // 1、创建订单
        $fake               = new OrderFake();
        $orderCreateCommand = OrderCreateCommand::from($fake->order());
        $order              = $this->orderCommandService()->create($orderCreateCommand);
        $this->assertInstanceOf(Order::class, $order);


        // 2、调用支付中
        $orderPayingCommand = OrderPayingCommand::from([ 'id' => $order->id, 'amount' => $order->payable_amount ]);
        $orderPayment       = $this->orderCommandService()->paying($orderPayingCommand);
        $this->assertInstanceOf(OrderPayment::class, $orderPayment);


        // 3、 设置支付成功
        $orderPaidCommand = $fake->paid([
                                            'id'               => $order->id,
                                            'order_payment_id' => $orderPayment->id,
                                            'amount'           => $orderPayment->payment_amount,
                                        ]);

        $this->orderCommandService()->paid($orderPaidCommand);

        $order = $this->orderRepository()->find($order->id);

        $this->assertEquals(PaymentStatusEnum::PAID->value, $order->payment_status->value);
        $this->assertEquals($order->payable_amount->value(), $order->payment_amount->value());
    }

}
