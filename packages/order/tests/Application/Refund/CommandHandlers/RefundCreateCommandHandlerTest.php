<?php

namespace RedJasmine\Order\Tests\Application\Refund\CommandHandlers;

use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCreateCommand;
use RedJasmine\Order\Domain\Models\Enums\RefundPhaseEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;

class RefundCreateCommandHandlerTest extends RefundCommandServiceTestCase
{



    // 售中阶段


    /**
     *  能创建仅退款单
     * 前提条件: 订单已支付
     * 步骤：
     *  1、发起一个产品的 仅退款的订单
     *  2、
     *  3、
     * 预期结果:
     *  1、创建退款单成功
     *  2、
     * @return void
     */
    public function test_can_create_only_refund_on_sale() : void
    {
        // 前提条件
        $order = $this->orderPaid();

        // 1、创建退款单
        foreach ($order->products as $product) {
            $command = RefundCreateCommand::from([
                                                     'id'               => $order->id,
                                                     'order_product_id' => $product->id,
                                                     'refund_type'      => RefundTypeEnum::REFUND->value,
                                                     'reason'           => fake()->randomElement([ '不想要了', '拍错了' ]),
                                                     'refund_amount'    => null,
                                                     'description'      => fake()->text,
                                                     'outer_refund_id'  => fake()->numerify('##########'),
                                                     'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
                                                 ]);


            $refundId = $this->refundCommandService()->create($command);
            $refund   = $this->refundRepository()->find($refundId);


            $this->assertEquals($order->id, $refund->order_id);
            $this->assertEquals($product->id, $refund->order_product_id);
            $this->assertEquals(RefundPhaseEnum::ON_SALE->value, $refund->phase->value);
            $this->assertEquals($command->refundType, $refund->refund_type);
            $this->assertEquals(RefundStatusEnum::WAIT_SELLER_AGREE->value, $refund->refund_status->value);

        }


    }

    /**
     *  能创建 退货退款单
     * 前提条件:
     * 步骤：
     *  1、
     *  2、
     *  3、
     * 预期结果:
     *  1、
     *  2、
     * @return void
     */
    public function test_can_crate_return_goods_refund_on_sale() : void
    {

        $order = $this->orderPaidAndShipping();


        // 创建退货退款单
        foreach ($order->products as $product) {
            $command = RefundCreateCommand::from([
                                                     'id'               => $order->id,
                                                     'order_product_id' => $product->id,
                                                     'refund_type'      => RefundTypeEnum::RETURN_GOODS_REFUND->value,
                                                     'reason'           => fake()->randomElement([ '不想要了', '拍错了' ]),
                                                     'refund_amount'    => null,
                                                     'description'      => fake()->text,
                                                     'outer_refund_id'  => fake()->numerify('##########'),
                                                     'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
                                                 ]);


            $refundId = $this->refundCommandService()->create($command);
            $refund   = $this->refundRepository()->find($refundId);


            $this->assertEquals($order->id, $refund->order_id);
            $this->assertEquals($product->id, $refund->order_product_id);
            $this->assertEquals(RefundPhaseEnum::ON_SALE->value, $refund->phase->value);
            $this->assertEquals($command->refundType, $refund->refund_type);
            $this->assertEquals(RefundStatusEnum::WAIT_SELLER_AGREE_RETURN->value, $refund->refund_status->value);

        }


    }

    /**
     *  能创建 换货单
     * 前提条件: 订单已发货
     * 步骤：
     *  1、创建换货单
     *  2、
     *  3、
     * 预期结果:
     *  1、换货单创建成功
     *  2、
     * @return void
     */
    public function test_can_crate_exchange_on_sale() : void
    {

        $order = $this->orderPaidAndShipping();


        // 创建退货退款单
        foreach ($order->products as $product) {
            $command = RefundCreateCommand::from([
                                                     'id'               => $order->id,
                                                     'order_product_id' => $product->id,
                                                     'refund_type'      => RefundTypeEnum::EXCHANGE->value,
                                                     'reason'           => fake()->randomElement([ '不想要了', '拍错了' ]),
                                                     'refund_amount'    => null,
                                                     'description'      => fake()->text,
                                                     'outer_refund_id'  => fake()->numerify('##########'),
                                                     'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
                                                 ]);


            $refundId = $this->refundCommandService()->create($command);
            $refund   = $this->refundRepository()->find($refundId);


            $this->assertEquals($order->id, $refund->order_id);
            $this->assertEquals($product->id, $refund->order_product_id);
            $this->assertEquals(RefundPhaseEnum::ON_SALE->value, $refund->phase->value);
            $this->assertEquals($command->refundType, $refund->refund_type);
            $this->assertEquals(RefundStatusEnum::WAIT_SELLER_AGREE_RETURN->value, $refund->refund_status->value);

        }


    }


    /**
     *  能创建补发
     * 前提条件: 订单商品已发货
     * 步骤：
     *  1、创建补发售后单
     *  2、
     *  3、
     * 预期结果:
     *  1、创建成功
     *  2、
     * @return void
     */
    public function test_can_crate_reshipment_on_sale() : void
    {

        $order = $this->orderPaidAndShipping();

        // 创建退货退款单
        foreach ($order->products as $product) {
            $command = RefundCreateCommand::from([
                                                     'id'               => $order->id,
                                                     'order_product_id' => $product->id,
                                                     'refund_type'      => RefundTypeEnum::RESHIPMENT->value,
                                                     'reason'           => fake()->randomElement([ '少了', '拍错了' ]),
                                                     'refund_amount'    => null,
                                                     'description'      => fake()->text,
                                                     'outer_refund_id'  => fake()->numerify('##########'),
                                                     'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
                                                 ]);


            $refundId = $this->refundCommandService()->create($command);
            $refund   = $this->refundRepository()->find($refundId);


            $this->assertEquals($order->id, $refund->order_id);
            $this->assertEquals($product->id, $refund->order_product_id);
            $this->assertEquals(RefundPhaseEnum::ON_SALE->value, $refund->phase->value);
            $this->assertEquals($command->refundType, $refund->refund_type);
            $this->assertEquals(RefundStatusEnum::WAIT_SELLER_AGREE->value, $refund->refund_status->value);

        }


    }


    // 售后阶段

    /**
     *  能创建仅退款单
     * 前提条件: 订单已确认
     * 步骤：
     *  1、发起一个产品的 仅退款的订单
     *  2、
     *  3、
     * 预期结果:
     *  1、创建退款单成功
     *  2、
     * @return void
     */
    public function test_can_create_only_refund_after_sale() : void
    {
        // 前提条件
        $order = $this->orderConfirmed();

        // 1、创建退款单
        foreach ($order->products as $product) {
            $command = RefundCreateCommand::from([
                                                     'id'               => $order->id,
                                                     'order_product_id' => $product->id,
                                                     'refund_type'      => RefundTypeEnum::REFUND->value,
                                                     'reason'           => fake()->randomElement([ '不想要了', '拍错了' ]),
                                                     'refund_amount'    => null,
                                                     'description'      => fake()->text,
                                                     'outer_refund_id'  => fake()->numerify('##########'),
                                                     'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
                                                 ]);


            $refundId = $this->refundCommandService()->create($command);
            $refund   = $this->refundRepository()->find($refundId);


            $this->assertEquals($order->id, $refund->order_id);
            $this->assertEquals($product->id, $refund->order_product_id);
            $this->assertEquals(RefundPhaseEnum::AFTER_SALE->value, $refund->phase->value);
            $this->assertEquals($command->refundType, $refund->refund_type);
            $this->assertEquals(RefundStatusEnum::WAIT_SELLER_AGREE->value, $refund->refund_status->value);

        }


    }


    /**
     *  能创建 退货退款单
     * 前提条件:
     * 步骤：
     *  1、
     *  2、
     *  3、
     * 预期结果:
     *  1、
     *  2、
     * @return void
     */
    public function test_can_crate_return_goods_refund_after_sale() : void
    {

        $order = $this->orderConfirmed();


        // 创建退货退款单
        foreach ($order->products as $product) {
            $command = RefundCreateCommand::from([
                                                     'id'               => $order->id,
                                                     'order_product_id' => $product->id,
                                                     'refund_type'      => RefundTypeEnum::RETURN_GOODS_REFUND->value,
                                                     'reason'           => fake()->randomElement([ '不想要了', '拍错了' ]),
                                                     'refund_amount'    => null,
                                                     'description'      => fake()->text,
                                                     'outer_refund_id'  => fake()->numerify('##########'),
                                                     'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
                                                 ]);


            $refundId = $this->refundCommandService()->create($command);
            $refund   = $this->refundRepository()->find($refundId);


            $this->assertEquals($order->id, $refund->order_id);
            $this->assertEquals($product->id, $refund->order_product_id);
            $this->assertEquals(RefundPhaseEnum::AFTER_SALE->value, $refund->phase->value);
            $this->assertEquals($command->refundType, $refund->refund_type);
            $this->assertEquals(RefundStatusEnum::WAIT_SELLER_AGREE_RETURN->value, $refund->refund_status->value);

        }


    }


    /**
     * 能创建 换货单
     * 前提条件: 订单已发货
     * 步骤：
     *  1、创建换货单
     *  2、
     *  3、
     * 预期结果:
     *  1、换货单创建成功
     *  2、
     * @return void
     */
    public function test_can_crate_exchange_after_sale() : void
    {

        $order = $this->orderConfirmed();


        // 创建退货退款单
        foreach ($order->products as $product) {
            $command = RefundCreateCommand::from([
                                                     'id'               => $order->id,
                                                     'order_product_id' => $product->id,
                                                     'refund_type'      => RefundTypeEnum::EXCHANGE->value,
                                                     'reason'           => fake()->randomElement([ '不想要了', '拍错了' ]),
                                                     'refund_amount'    => null,
                                                     'description'      => fake()->text,
                                                     'outer_refund_id'  => fake()->numerify('##########'),
                                                     'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
                                                 ]);


            $refundId = $this->refundCommandService()->create($command);
            $refund   = $this->refundRepository()->find($refundId);


            $this->assertEquals($order->id, $refund->order_id);
            $this->assertEquals($product->id, $refund->order_product_id);

            $this->assertEquals($command->refundType, $refund->refund_type);
            $this->assertEquals(RefundPhaseEnum::AFTER_SALE->value, $refund->phase->value);
            $this->assertEquals(RefundStatusEnum::WAIT_SELLER_AGREE_RETURN->value, $refund->refund_status->value);

        }


    }


}
