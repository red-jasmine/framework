<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;


use RedJasmine\Order\Domain\Events\OrderPaidEvent;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class OrderPaidCommandHandler extends AbstractOrderCommandHandler
{


    public function handle(OrderPaidCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $order = $this->findByNo($command->orderNo);

            // TODO 换成 通过 仓库查询
            $orderPayment                     = $order->payments->where('id', $command->orderPaymentId)->firstOrFail();
            $orderPayment->payment_amount     = $command->amount;
            $orderPayment->payment_time       = $command->paymentTime;
            $orderPayment->payment_type       = $command->paymentType;
            $orderPayment->payment_id         = $command->paymentId;
            $orderPayment->payment_channel    = $command->paymentChannel;
            $orderPayment->payment_channel_no = $command->paymentChannelNo;
            $orderPayment->payment_method     = $command->paymentMethod;
            $order->paid($orderPayment);

            $this->service->repository->store($order);

            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        OrderPaidEvent::dispatch($order);

        return true;
    }

}
