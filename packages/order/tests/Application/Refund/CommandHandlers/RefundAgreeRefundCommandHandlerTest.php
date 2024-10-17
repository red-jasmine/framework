<?php

namespace RedJasmine\Order\Tests\Application\Refund\CommandHandlers;

use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeRefundCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCreateCommand;
use RedJasmine\Order\Domain\Models\Enums\OrderRefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;

class RefundAgreeRefundCommandHandlerTest extends RefundCommandServiceTestCase
{

    /**
     * @return int[]
     */
    public function orderPaidAndCreateRefund() : array
    {
        $order = $this->orderPaid();

        $refunds = [];
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


            $refunds[] = $this->refundCommandService()->create($command);


        }
        return $refunds;

    }

    /**
     * @return int[]
     */
    public function orderPaidAndShippingAndCreateRefund() : array
    {
        $order = $this->orderPaidAndShipping();

        $refunds = [];
        // 1、创建退款单
        foreach ($order->products as $product) {
            $command = RefundCreateCommand::from([
                                                     'id'               => $order->id,
                                                     'order_product_id' => $product->id,
                                                     'refund_type'      => RefundTypeEnum::RETURN_GOODS_REFUND->value,
                                                     'reason'           => fake()->randomElement([ '不合适', '拍错了' ]),
                                                     'refund_amount'    => null,
                                                     'description'      => fake()->text,
                                                     'outer_refund_id'  => fake()->numerify('##########'),
                                                     'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
                                                 ]);


            $refunds[] = $this->refundCommandService()->create($command);


        }
        return $refunds;

    }

    /**
     *  能同意退款
     * 前提条件: 订单支付后、创建退款单
     * 步骤：
     *  1、同意退款
     *  2、
     *  3、
     * 预期结果:
     *  1、退款单：退款状态->同意退款、
     *  2、子商品单:退款金额 = 付款金额、订单状态=关闭、退款状态=全部退款
     *  3、订单: 退款金额= 、退款状态 = 全部退款
     * @return void
     */
    public function test_can_agree_typeof_refund() : void
    {
        // 前提条件
        $refunds = $this->orderPaidAndCreateRefund();

        foreach ($refunds as $rid) {
            $refund  = $this->refundRepository()->find($rid);
            $command = RefundAgreeRefundCommand::from([
                                                          'rid'    => $rid,
                                                          'amount' => $refund->refund_amount
                                                      ]);

            $this->refundCommandService()->agreeRefund($command);

            $refund = $this->refundRepository()->find($rid);

            $this->assertEquals($refund->product->divided_payment_amount->value(), $refund->product->refund_amount->value());

            $this->assertEquals(OrderRefundStatusEnum::ALL_REFUND->value, $refund->product->refund_status->value);

            $this->assertEquals(RefundStatusEnum::REFUND_SUCCESS->value, $refund->refund_status->value);

        }

    }


}
