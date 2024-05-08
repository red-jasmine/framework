<?php

namespace RedJasmine\Order\Tests\UI\Http\Buyer;

use RedJasmine\Order\Application\UserCases\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeRefundCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeReturnGoodsCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundConfirmCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundRejectCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundReshipmentCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderShippingLogisticsCommand;
use RedJasmine\Order\Domain\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Enums\RefundTypeEnum;
use RedJasmine\Order\Domain\Enums\ShippingTypeEnum;
use RedJasmine\Order\Tests\Fixtures\Orders\OrderFake;


class RefundTest extends Base
{


    protected function setUp() : void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user();
    }

    /**
     *
     * @param OrderFake $orderFake
     *
     * @return array
     */
    protected function premise_create_order_and_paid(OrderFake $orderFake = new OrderFake()) : array
    {


        // 创建订单
        //$orderFake   = new OrderFake();
        $requestData = $orderFake->order();
        unset($requestData['buyer']);
        $response = $this->postJson(route('order.buyer.orders.store', [], false), $requestData);

        $this->assertEquals(201, $response->status());

        $orderData      = $response->json('data');
        $orderId        = $orderData['id'];
        $products       = $orderData['products'];
        $orderProductId = $products[0]['id'];

        // 2、发起支付

        $payingRequestData = [ 'id' => $orderId ];
        $payingResponse    = $this->postJson(route('order.buyer.orders.paying', [], false), $payingRequestData);
        $this->assertEquals(200, $payingResponse->status());
        $payingResult = $payingResponse->json('data');
        $orderPayment = $payingResult['order_payment'];
        $this->assertEquals($orderId, $payingResult['id']);


        // 3、完成支付

        $paymentCommand = OrderPaidCommand::from([
                                                     'id'                 => $orderId,
                                                     'order_payment_id'   => $orderPayment['id'],
                                                     'amount'             => $payingResult['amount'],
                                                     'payment_type'       => 'payment',
                                                     'payment_id'         => fake()->numberBetween(1000000, 999999999),
                                                     'payment_time'       => date('Y-m-d H:i:s'),
                                                     'payment_channel'    => 'alipay',
                                                     'payment_channel_no' => fake()->numerify('pay-########'),
                                                     'payment_method'     => 'alipay',
                                                 ]);


        $paidResult = $this->orderCommandService()->paid($paymentCommand);

        $this->assertTrue($paidResult);

        return $orderData;

    }


    /**
     * @param OrderFake $orderFake
     *
     * @return array
     */
    protected function premise_create_order_paid_shipping(OrderFake $orderFake = new OrderFake()) : array
    {

        $orderFake->shippingType = ShippingTypeEnum::EXPRESS;

        $orderData      = $this->premise_create_order_and_paid($orderFake);
        $orderId        = $orderData['id'];
        $products       = $orderData['products'];
        $orderProductId = $products[0]['id'];
        // 发货
        $command = OrderShippingLogisticsCommand::from([
                                                           'id'                   => $orderId,
                                                           'express_company_code' => fake()->randomElement([ 'yunda' ]),
                                                           'express_no'           => fake()->numberBetween(111111111, 999999999)
                                                       ]);
        $this->orderCommandService()->shippingLogistics($command);

        return $orderData;

    }

    /**
     * @test 创建退款买家同意
     * 前提条件: 创建订单、发起支付、设置付款成功
     * 步骤:
     *  1、申请仅退款
     *  2、卖家同意退款
     *  3、查询退款状态
     * 预期结果：
     *  1、同意退款后为退款成功
     *
     * @return void
     */
    public function can_refund_create_and_seller_agree() : void
    {


        $orderData      = $this->premise_create_order_and_paid();
        $orderId        = $orderData['id'];
        $products       = $orderData['products'];
        $orderProductId = $products[0]['id'];
        // 4、申请退款

        $refundCreateRequestData = [
            'id'               => $orderId,
            'order_product_id' => $orderProductId,
            'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
            'refund_type'      => RefundTypeEnum::REFUND->value,
            'refund_amount'    => null,
            'reason'           => '买错了',
            'description'      => '',
            'order_refund_id'  => fake()->numerify('out-refund-id-########'),

        ];

        $refundCreateResponse = $this->postJson(route('order.buyer.refunds.store', [], false), $refundCreateRequestData);

        $this->assertEquals(200, $refundCreateResponse->status());

        $refundCreateResponseData = $refundCreateResponse->json('data');

        $rid = $refundCreateResponseData['rid'];


        // 5、同意退款

        $agreeRefundCommand = RefundAgreeRefundCommand::from([ 'rid' => $rid ]);
        $this->refundCommandService()->agreeRefund($agreeRefundCommand);


        // 6、查询退款详情

        $showRequestData = [ 'refund' => $rid ];
        $showResponse    = $this->getJson(route('order.buyer.refunds.show', $showRequestData, false));

        $this->assertEquals(200, $showResponse->status());

        $showResponseData = $showResponse->json('data');

        $this->assertEquals(RefundStatusEnum::REFUND_SUCCESS->value, $showResponseData['refund_status']);

    }

    /**
     * @test 创建退款卖家拒绝后取消
     *       // 前提:订单支付成功
     *       // 步骤:
     *       // 1、申请退款
     *       // 2、卖家拒绝
     *       // 3、买家取消
     *       预期结果：
     *       1、卖家拒绝后状态是拒绝状态
     *       2、能取消成功
     * @return void
     */
    public function can_refund_create_and_seller_reject_after_cancel() : void
    {

        // 前提:订单支付成功
        $orderData      = $this->premise_create_order_and_paid();
        $orderId        = $orderData['id'];
        $products       = $orderData['products'];
        $orderProductId = $products[0]['id'];
        // 步骤:
        // 1、申请退款

        $refundCreateRequestData = [
            'id'               => $orderId,
            'order_product_id' => $orderProductId,
            'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
            'refund_type'      => RefundTypeEnum::REFUND->value,
            'refund_amount'    => null,
            'reason'           => '买错了',
            'description'      => '',
            'order_refund_id'  => fake()->numerify('out-refund-id-########'),

        ];
        $refundCreateResponse    = $this->postJson(route('order.buyer.refunds.store', [], false), $refundCreateRequestData);
        $this->assertEquals(200, $refundCreateResponse->status());
        $refundCreateResponseData = $refundCreateResponse->json('data');
        $rid                      = $refundCreateResponseData['rid'];
        // 2、卖家拒绝
        $rejectRefundCommand = RefundRejectCommand::from([ 'rid' => $rid, 'reason' => '不支持退款' ]);
        $this->refundCommandService()->reject($rejectRefundCommand);


        $showRequestData = [ 'refund' => $rid ];
        $showResponse    = $this->getJson(route('order.buyer.refunds.show', $showRequestData, false));
        $this->assertEquals(200, $showResponse->status());
        $showResponseData = $showResponse->json('data');
        $this->assertEquals(RefundStatusEnum::SELLER_REJECT_BUYER->value, $showResponseData['refund_status']);


        // 3、买家取消

        $cancelRefundRequestData = [ 'rid' => $rid ];
        $cancelRefundResponse    = $this->postJson(route('order.buyer.refunds.cancel', [], false), $cancelRefundRequestData);
        $cancelRefundResponse->assertStatus(200);
        $this->assertEquals(0, $cancelRefundResponse->json('code'));

    }


    /**
     * @test 取消退款
     * 前提条件:
     * 步骤：1、申请退款 2、取消退款 3、查看退款单状态
     * @return void
     */
    public function can_cancel_refund() : void
    {
        // 前置条件
        $orderData      = $this->premise_create_order_and_paid();
        $orderId        = $orderData['id'];
        $products       = $orderData['products'];
        $orderProductId = $products[0]['id'];

        // 申请退款
        $refundCreateRequestData = [
            'id'               => $orderId,
            'order_product_id' => $orderProductId,
            'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
            'refund_type'      => RefundTypeEnum::REFUND->value,
            'refund_amount'    => null,
            'reason'           => '买错了',
            'description'      => '',
            'order_refund_id'  => fake()->numerify('out-refund-id-########'),

        ];
        $refundCreateResponse    = $this->postJson(route('order.buyer.refunds.store', [], false), $refundCreateRequestData);

        $this->assertEquals(200, $refundCreateResponse->status());

        $refundCreateResponseData = $refundCreateResponse->json('data');

        $rid = $refundCreateResponseData['rid'];


        // 2、取消退款

        $cancelRefundRequestData = [ 'rid' => $rid ];

        $cancelRefundResponse = $this->postJson(route('order.buyer.refunds.cancel', [], false), $cancelRefundRequestData);

        $this->assertEquals(200, $cancelRefundResponse->status());
        $this->assertEquals(0, $cancelRefundResponse->json('code'));


    }


    /**
     * @test 退货退款 卖家同意
     * 前提条件: 订单支付成功、卖家发货
     * 步骤：
     *  1、申请退款退款
     *  2、卖家同意退货
     *  3、寄回物品
     *  4、卖家确认退款
     *  5、查询 状态
     * 预期结果:
     *  1、退款成功
     * @return void
     */
    public function can_return_goods_and_refund() : void
    {

        $orderData      = $this->premise_create_order_paid_shipping();
        $orderId        = $orderData['id'];
        $products       = $orderData['products'];
        $orderProductId = $products[0]['id'];

        // 1、申请退货退款

        $refundCreateRequestData = [
            'id'               => $orderId,
            'order_product_id' => $orderProductId,
            'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
            'refund_type'      => RefundTypeEnum::RETURN_GOODS_REFUND->value,
            'refund_amount'    => null,
            'reason'           => '大了',
            'description'      => '',
            'order_refund_id'  => fake()->numerify('out-refund-id-########'),

        ];
        $refundCreateResponse    = $this->postJson(route('order.buyer.refunds.store', [], false), $refundCreateRequestData);

        $this->assertEquals(200, $refundCreateResponse->status());

        $refundCreateResponseData = $refundCreateResponse->json('data');

        $rid = $refundCreateResponseData['rid'];
        // 2、卖家同意退货

        $agreeReturnGoodsCommand = RefundAgreeReturnGoodsCommand::from([ 'rid' => $rid ]);
        $this->refundCommandService()->agreeReturnGoods($agreeReturnGoodsCommand);

        // 3、寄回物品

        $returnGoodsRequestData = [
            'rid'                  => $rid,
            'express_company_code' => fake()->randomElement([ 'yunda' ]),
            'express_no'           => (string)fake()->numberBetween(111111111, 999999999)
        ];
        $returnGoodsResponse    = $this->postJson(route('order.buyer.refunds.return-goods', [], false), $returnGoodsRequestData);

        $this->assertEquals(200, $returnGoodsResponse->status());
        // 4、卖家确认退款

        $agreeRefundCommand = RefundAgreeRefundCommand::from([ 'rid' => $rid ]);
        $this->refundCommandService()->agreeRefund($agreeRefundCommand);


        // 5、查询 状态

        $showRequestData = [ 'refund' => $rid ];
        $showResponse    = $this->getJson(route('order.buyer.refunds.show', $showRequestData, false));
        $this->assertEquals(200, $showResponse->status());
        $showResponseData = $showResponse->json('data');
        $this->assertEquals(RefundStatusEnum::REFUND_SUCCESS->value, $showResponseData['refund_status']);


    }

    /**
     * @test 退货退款 卖家拒绝退货
     * 前提条件: 订单支付成功、卖家发货
     * 步骤：
     *  1、申请退款退款
     *  2、卖家拒绝退货
     *  3、查询退款详情
     *  4、取消退款单
     * 预期结果:
     *  1、查询详情为拒绝
     *  2、取消成功
     * @return void
     */
    public function can_return_goods_seller_reject() : void
    {

        $orderData      = $this->premise_create_order_paid_shipping();
        $orderId        = $orderData['id'];
        $products       = $orderData['products'];
        $orderProductId = $products[0]['id'];

        // 1、申请退货退款

        $refundCreateRequestData = [
            'id'               => $orderId,
            'order_product_id' => $orderProductId,
            'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
            'refund_type'      => RefundTypeEnum::RETURN_GOODS_REFUND->value,
            'refund_amount'    => null,
            'reason'           => '大了',
            'description'      => '',
            'order_refund_id'  => fake()->numerify('out-refund-id-########'),

        ];
        $refundCreateResponse    = $this->postJson(route('order.buyer.refunds.store', [], false), $refundCreateRequestData);

        $this->assertEquals(200, $refundCreateResponse->status());

        $refundCreateResponseData = $refundCreateResponse->json('data');

        $rid = $refundCreateResponseData['rid'];

        // 2、卖家拒绝退货

        $refundRejectCommand = RefundRejectCommand::from([ 'rid' => $rid, 'reason' => '不是质量问题' ]);
        $this->refundCommandService()->reject($refundRejectCommand);
        // 3、查询退款详情
        $showRequestData = [ 'refund' => $rid ];
        $showResponse    = $this->getJson(route('order.buyer.refunds.show', $showRequestData, false));
        $this->assertEquals(200, $showResponse->status());
        $showResponseData = $showResponse->json('data');
        $this->assertEquals(RefundStatusEnum::SELLER_REJECT_BUYER->value, $showResponseData['refund_status']);
        // 4、取消退款单

        $cancelRefundRequestData = [ 'rid' => $rid ];
        $cancelRefundResponse    = $this->postJson(route('order.buyer.refunds.cancel', [], false), $cancelRefundRequestData);
        $this->assertEquals(200, $cancelRefundResponse->status());
        $this->assertEquals(0, $cancelRefundResponse->json('code'));
    }


    /**
     * @test 能进行换货操作
     *       前提条件： 订单支付、并发货
     *       步骤：
     *       1、申请换货
     *       2、卖家同意退回货物
     *       3、买家寄回货物
     *       4、买家确认
     *       6、卖家重新发货
     *       5、查询验证
     *       期望结果：
     *       1、能正常换货
     * @return void
     */
    public function can_change_goods() : void
    {
        // 前提条件
        $orderData      = $this->premise_create_order_paid_shipping();
        $orderId        = $orderData['id'];
        $products       = $orderData['products'];
        $orderProductId = $products[0]['id'];

        //步骤：
        //1、申请换货
        $refundCreateRequestData = RefundCreateCommand::from(
            [
                'id'               => $orderId,
                'order_product_id' => $orderProductId,
                'images'           => [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ],
                'refund_type'      => RefundTypeEnum::EXCHANGE->value,
                'refund_amount'    => null,
                'reason'           => '大了换小一码的',
                'description'      => '',
                'order_refund_id'  => fake()->numerify('out-refund-id-########'),

            ]
        )->toArray();
        $refundCreateResponse    = $this->postJson(route('order.buyer.refunds.store', [], false), $refundCreateRequestData);

        $this->assertEquals(200, $refundCreateResponse->status());

        $refundCreateResponseData = $refundCreateResponse->json('data');

        $rid = $refundCreateResponseData['rid'];
        //2、卖家同意退回货物
        $agreeReturnGoodsCommand = RefundAgreeReturnGoodsCommand::from([ 'rid' => $rid ]);
        $this->refundCommandService()->agreeReturnGoods($agreeReturnGoodsCommand);

        //3、买家寄回货物

        $returnGoodsRequestData = [
            'rid'                  => $rid,
            'express_company_code' => fake()->randomElement([ 'yunda' ]),
            'express_no'           => (string)fake()->numberBetween(111111111, 999999999)
        ];
        $returnGoodsResponse    = $this->postJson(route('order.buyer.refunds.return-goods', [], false), $returnGoodsRequestData);

        $this->assertEquals(200, $returnGoodsResponse->status());

        // 4、卖家确认
        $confirmCommand = RefundConfirmCommand::from([ 'rid' => $rid ]);

        $this->refundCommandService()->confirm($confirmCommand);


        //5、卖家重新发货
        $reshippingCommand = RefundReshipmentCommand::from([
                                                               'rid'                  => $rid,
                                                               'express_company_code' => fake()->randomElement([ 'yunda' ]),
                                                               'express_no'           => (string)fake()->numberBetween(111111111, 999999999)
                                                           ]);

        $this->refundCommandService()->reshipment($reshippingCommand);
        //6、查询验证
        $showRequestData = [ 'refund' => $rid ];
        $showResponse    = $this->getJson(route('order.buyer.refunds.show', $showRequestData, false));
        $this->assertEquals(200, $showResponse->status());
        $showResponseData = $showResponse->json('data');
        $this->assertEquals(RefundStatusEnum::REFUND_SUCCESS->value, $showResponseData['refund_status']);


    }
}
