<?php

namespace RedJasmine\Order\Tests\Application\Order\CommandHandlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Tests\Application\ApplicationTestCase;

class OrderProgressCommandHandlerTest extends ApplicationTestCase
{



    protected function orderPaidAndShippingVirtual() : Order
    {
        // 1、创建订单
        $fake               = $this->fake();
        $fake->productCount = 2;
        $fake->unit         = 10;
        $fake->shippingType = ShippingTypeEnum::VIRTUAL;

        $orderCreateCommand = OrderCreateCommand::from($fake->order());
        $order              = $this->orderCommandService()->create($orderCreateCommand);
        //$this->assertInstanceOf(Order::class, $order);


        // 2、调用支付中
        $orderPayingCommand = OrderPayingCommand::from([ 'id' => $order->id, 'amount' => $order->payable_amount ]);
        $orderPayment       = $this->orderCommandService()->paying($orderPayingCommand);
        //$this->assertInstanceOf(OrderPayment::class, $orderPayment);


        // 3、 设置支付成功
        $orderPaidCommand = $fake->paid([
                                            'id'               => $order->id,
                                            'order_payment_id' => $orderPayment->id,
                                            'amount'           => $orderPayment->payment_amount,
                                        ]);

        $this->orderCommandService()->paid($orderPaidCommand);


        // 3、子商品单  设置开始发货


        foreach ($order->products as $product) {
            $orderShippingVirtualCommand = $this->fake()->shippingVirtual([
                                                                              'id'               => $order->id,
                                                                              'order_product_id' => $product->id,
                                                                              'is_finished'      => false,
                                                                          ]);

            $this->orderCommandService()->shippingVirtual($orderShippingVirtualCommand);
        }


        return $order;
    }


    /**
     * 虚拟商品子商品单 能进行设置进度
     * 前提条件: 虚拟商品 已开始发货
     * 步骤：
     *  1、设置 子商品单 绝对进度
     *  2、设置 子商品单 相对增加进度
     *
     * 预期结果:
     *  1、 和 设置进度相等
     *  2、和 第一次设置的 和相等
     * @return void
     */
    public function test_can_set_progress() : void
    {
        $order = $this->orderPaidAndShippingVirtual();

        // 测设置绝对进度
        $orderProgressList = [];
        foreach ($order->products as $product) {
            $orderProgressCommand            = $this->fake()->progress([
                                                                           'id'               => $order->id,
                                                                           'order_product_id' => $product->id,
                                                                           'progress'         => fake()->numberBetween(1, 100),
                                                                       ]);
            $orderProgressList[$product->id] = $orderProgressCommand;
            $newProgress                     = $this->orderCommandService()->progress($orderProgressCommand);
            $this->assertEquals($orderProgressCommand->progress, $newProgress);
        }


        // 测试设置相对进度
        foreach ($order->products as $product) {
            $data                 = [
                'id'               => $order->id,
                'order_product_id' => $product->id,
                'progress'         => 2,
                'is_absolute'      => false,// 不是绝对的
            ];
            $orderProgressCommand = $this->fake()->progress($data);

            $newProgress = $this->orderCommandService()->progress($orderProgressCommand);

            $progress = $orderProgressList[$product->id]->progress + $orderProgressCommand->progress;
            $this->assertEquals($progress, $newProgress);
        }


    }


}
