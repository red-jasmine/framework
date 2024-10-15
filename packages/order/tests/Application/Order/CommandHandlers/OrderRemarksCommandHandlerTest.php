<?php

namespace RedJasmine\Order\Tests\Application\Order\CommandHandlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Tests\Application\ApplicationTestCase;
use RedJasmine\Order\Tests\Fixtures\Orders\OrderFake;

class OrderRemarksCommandHandlerTest extends ApplicationTestCase
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
     *  1、对主订单设置备注
     *  2、对子商品单 设置备注
     *
     * 预期结果:
     *  1、查询后主订单的备注一致
     *  2、查询后子商品单的备注一致
     *
     * @return void
     */
    public function test_can_remarks() : void
    {
        $order = $this->create_order();

        //步骤：
        // 1、对主订单设置备注
        $orderRemarks = OrderRemarksCommand::from([ 'id' => $order->id, 'remarks' => fake()->text() ]);
        $this->orderCommandService()->sellerRemarks($orderRemarks);
        $this->orderCommandService()->buyerRemarks($orderRemarks);

        $order = $this->orderRepository()->find($order->id);

        $this->assertEquals($orderRemarks->remarks, $order->info->seller_remarks);
        $this->assertEquals($orderRemarks->remarks, $order->info->buyer_remarks);
        foreach ($order->products as $product) {
            $this->assertNotEquals($orderRemarks->remarks, $product->info->seller_remarks);
            $this->assertNotEquals($orderRemarks->remarks, $product->info->buyer_remarks);
        }
        // 2、对子商品单 设置备注
        $remarks = fake()->text;
        foreach ($order->products as $product) {
            $orderRemarks = OrderRemarksCommand::from([ 'id' => $order->id, 'order_product_id' => $product->id, 'remarks' => $remarks ]);
            $this->orderCommandService()->sellerRemarks($orderRemarks);
            $this->orderCommandService()->buyerRemarks($orderRemarks);
        }


        $order = $this->orderRepository()->find($order->id);

        $this->assertNotEquals($remarks, $order->info->seller_remarks);
        $this->assertNotEquals($remarks, $order->info->buyer_remarks);
        foreach ($order->products as $product) {
            $this->assertEquals($remarks, $product->info->seller_remarks);
            $this->assertEquals($remarks, $product->info->buyer_remarks);
        }

    }

}
