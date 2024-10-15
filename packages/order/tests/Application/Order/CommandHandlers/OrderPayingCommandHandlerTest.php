<?php

namespace RedJasmine\Order\Tests\Application\Order\CommandHandlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Tests\Application\ApplicationTestCase;
use RedJasmine\Order\Tests\Fixtures\Orders\OrderFake;

class OrderPayingCommandHandlerTest extends ApplicationTestCase
{


    /**
     * 创建订单
     * 前提条件: 准备订单数据
     * 步骤：
     *  1、创建订单
     *  2、调用支付
     *  3、
     * 预期结果:
     *  1、有返回支付单
     *  2、
     * @return void
     */
    public function test_can_order_paying() : void
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
    }

}
