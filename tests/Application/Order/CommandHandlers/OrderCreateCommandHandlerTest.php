<?php

namespace RedJasmine\Order\Tests\Application\Order\CommandHandlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Tests\Application\ApplicationTestCase;
use RedJasmine\Order\Tests\Fixtures\Orders\OrderFake;

class OrderCreateCommandHandlerTest extends ApplicationTestCase
{


    /**
     * 创建标准流程订单 SOP
     * 前提条件: 准备订单数据
     * 步骤：
     *  1、创建数据
     *  2、执行
     *  3、
     * 预期结果: TODO
     *  1、有返回结果
     *  2、验证金额
     *  3、验证状态
     * @return void
     */
    public function test_can_create_sop_order() : void
    {

        $fake               = new OrderFake();
        $orderCreateCommand = OrderCreateCommand::from($fake->order());

        $order = $this->orderCommandService()->create($orderCreateCommand);
        $this->assertInstanceOf(Order::class, $order);

        $this->assertEquals($orderCreateCommand->orderType, $order->order_type);
        $this->assertCount($orderCreateCommand->products->count(), $order->products);

    }

}
