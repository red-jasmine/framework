<?php

namespace RedJasmine\Order\Tests\Application\Refund\CommandHandlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Tests\Application\ApplicationTestCase;

class RefundCommandServiceTestCase extends ApplicationTestCase
{

    protected function orderPaid() : Order
    {
        // 1、创建订单
        $fake = $this->fake();

        $fake->productCount = 2;


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
        return $order;
    }


    public function orderPaidAndShipping() : Order
    {
        $order = $this->orderPaid();

        $orderShippingLogisticsCommand = $this->fake()->shippingLogistics([ 'id' => $order->id ]);

        $this->orderCommandService()->shippingLogistics($orderShippingLogisticsCommand);

        return $order;
    }


    public function orderConfirmed() : Order
    {

        $order = $this->orderPaidAndShipping();

        $command = OrderConfirmCommand::from([ 'id' => $order->id ]);
        $this->orderCommandService()->confirm($command);

        return $order;

    }





}
