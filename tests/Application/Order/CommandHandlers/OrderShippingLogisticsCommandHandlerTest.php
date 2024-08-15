<?php

namespace RedJasmine\Order\Tests\Application\Order\CommandHandlers;

use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Tests\Application\ApplicationTestCase;
use RedJasmine\Order\Tests\Fixtures\Orders\OrderFake;

class OrderShippingLogisticsCommandHandlerTest extends ApplicationTestCase
{


    public function fake() : OrderFake
    {
        $fake               = parent::fake();
        $fake->shippingType = ShippingTypeEnum::EXPRESS;
        return $fake;
    }


    /**
     *  订单能物流发货
     * 前提条件: 准备订单数据
     * 步骤：
     *  1、创建订单
     *  2、调用支付
     *  3、设置支付成功
     *  4、发货操作
     *
     * 预期结果:
     *  1、订单状态为待买家确认、发货状态已发货、发货时间存在
     *  2、物流单存在
     * @return void
     */
    public function test_can_shipping_logistics() : void
    {


        $order = $this->orderPaid();
        //4、物流发货

        $orderShippingLogisticsCommand = $this->fake()->shippingLogistics([
                                                                              'id' => $order->id
                                                                          ]);


        $this->orderCommandService()->shippingLogistics($orderShippingLogisticsCommand);


        $order = $this->orderRepository()->find($order->id);

        $this->assertEquals(OrderStatusEnum::WAIT_BUYER_CONFIRM_GOODS->value, $order->order_status->value, '订单状态');
        $this->assertEquals(ShippingStatusEnum::SHIPPED->value, $order->shipping_status->value, '发货状态');
        $this->assertNotEmpty($order->shipping_time);

    }

    /**
     *  订单能进行菜饭发货
     * 前提条件: 订单已支付
     * 步骤：
     *  1、对第一个子商品单 发货
     *  2、对 剩余的子商品单 发货
     *  3、
     * 预期结果:
     *  1、第一次发货后  订单状态为待卖家发货、发货状态为部分发货、子商品单 已发货的为已发货、未发货的显示未发货
     *  2、第二次发货后  订单状态为 待买家确认、
     * @return void
     */
    public function test_can_split_shipping_logistics() : void
    {
        $order = $this->orderPaid();

        // 1、第一次发货

        $orderProducts = [ $order->products->pluck('id')->toArray()[0] ];

        $orderShippingLogisticsCommand = $this->fake()->shippingLogistics([

                                                                              'id'             => $order->id,
                                                                              'is_split'       => true,
                                                                              'order_products' => $orderProducts
                                                                          ]);


        $this->orderCommandService()->shippingLogistics($orderShippingLogisticsCommand);


        $order = $this->orderRepository()->find($order->id);

        $this->assertEquals(OrderStatusEnum::WAIT_SELLER_SEND_GOODS->value, $order->order_status->value, '订单状态');
        $this->assertEquals(ShippingStatusEnum::PART_SHIPPED->value, $order->shipping_status->value, '发货状态');
        $this->assertNotEmpty($order->shipping_time);
        foreach ($order->products as $product) {
            if (in_array($product->id, $orderProducts, true)) {
                // 刚刚发货的子商品单
                $this->assertEquals(OrderStatusEnum::WAIT_BUYER_CONFIRM_GOODS->value, $product->order_status->value, '订单状态');
                $this->assertEquals(ShippingStatusEnum::SHIPPED->value, $product->shipping_status->value, '发货状态');
                $this->assertNotEmpty($product->shipping_time);
            } else {
                $this->assertEquals(OrderStatusEnum::WAIT_SELLER_SEND_GOODS->value, $product->order_status->value, '订单状态');
                $this->assertEquals(ShippingStatusEnum::WAIT_SEND->value, $product->shipping_status->value, '发货状态');

            }
        }
        // 2、第二次发货
        $orderProducts = $order->products->pluck('id')->toArray();
        unset($orderProducts[0]);

        $orderShippingLogisticsCommand = $this->fake()->shippingLogistics([

                                                                              'id'             => $order->id,
                                                                              'is_split'       => true,
                                                                              'order_products' => $orderProducts
                                                                          ]);


        $this->orderCommandService()->shippingLogistics($orderShippingLogisticsCommand);

        $order = $this->orderRepository()->find($order->id);

        $this->assertEquals(OrderStatusEnum::WAIT_BUYER_CONFIRM_GOODS->value, $order->order_status->value, '订单状态');
        $this->assertEquals(ShippingStatusEnum::SHIPPED->value, $order->shipping_status->value, '发货状态');
        $this->assertNotEmpty($order->shipping_time);
        foreach ($order->products as $product) {
            $this->assertEquals(OrderStatusEnum::WAIT_BUYER_CONFIRM_GOODS->value, $product->order_status->value, '订单状态');
            $this->assertEquals(ShippingStatusEnum::SHIPPED->value, $product->shipping_status->value, '发货状态');
            $this->assertNotEmpty($product->shipping_time);
        }


    }


}
