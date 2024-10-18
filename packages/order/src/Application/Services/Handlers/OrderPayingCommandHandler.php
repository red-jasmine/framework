<?php

namespace RedJasmine\Order\Application\Services\Handlers;

use Illuminate\Support\Facades\DB;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Domain\Models\OrderPayment;
use Throwable;

class OrderPayingCommandHandler extends AbstractOrderCommandHandler
{


    public function handle(OrderPayingCommand $command) : OrderPayment
    {
        $order        = $this->find($command->id);
        $orderPayment = OrderPayment::newModel();

        $orderPayment->payment_amount = $command->amount;
        $orderPayment->amount_type    = $command->amountType;
        $orderPayment->creator        = $order->updater;


        try {
            DB::beginTransaction();
            $order->paying($orderPayment);
            $this->orderRepository->store($order);
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }


        return $orderPayment;
    }

}
