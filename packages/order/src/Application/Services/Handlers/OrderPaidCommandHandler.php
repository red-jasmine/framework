<?php

namespace RedJasmine\Order\Application\Services\Handlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderPaidCommand;
use RedJasmine\Order\Domain\Events\OrderPaidEvent;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;

class OrderPaidCommandHandler extends AbstractOrderCommandHandler
{


    public function handle(OrderPaidCommand $command) : bool
    {


        $order                            = $this->find($command->id);
        $orderPayment                     = $order->payments->where('id', $command->orderPaymentId)->firstOrFail();
        $orderPayment->payment_amount     = $command->amount;
        $orderPayment->payment_time       = $command->paymentTime;
        $orderPayment->payment_type       = $command->paymentType;
        $orderPayment->payment_id         = $command->paymentId;
        $orderPayment->payment_channel    = $command->paymentChannel;
        $orderPayment->payment_channel_no = $command->paymentChannelNo;
        $orderPayment->payment_method     = $command->paymentMethod;
        $orderPayment->updater            = $order->updater;

        $order->paid($orderPayment);

        $this->orderRepository->store($order);

        OrderPaidEvent::dispatch($order);

        return true;
    }

}
