<?php

namespace RedJasmine\Order\Tests\Application\Order\CommandHandlers;

use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Tests\Application\ApplicationTestCase;
use RedJasmine\Order\Tests\Fixtures\Orders\OrderFake;

class OrderShippingCardKeyCommandHandlerTest extends ApplicationTestCase
{

    public function fake() : OrderFake
    {
        $fake               = parent::fake();
        $fake->productCount = 2;
        $fake->shippingType = ShippingTypeEnum::CDK;
        return $fake;
    }


    /**
     *  订单能卡密发货
     * 前提条件: 已支付卡密订单
     * 步骤：
     *  1、对所有订单进行卡密发货
     *
     * 预期结果:
     *  1、
     * @return void
     */
    public function test_can_shipping_card_key() : void
    {


        $order = $this->orderPaid();
        //1、所有 子商品单 卡密发货

        foreach ($order->products as $product) {
            $orderShippingCardKeyCommand = $this->fake()->shippingCardKey([
                                                                              'id'               => $order->id,
                                                                              'order_product_id' => $product->id,
                                                                              'num'              => $product->num
                                                                          ]);


            $this->orderCommandService()->shippingCardKey($orderShippingCardKeyCommand);
        }


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
