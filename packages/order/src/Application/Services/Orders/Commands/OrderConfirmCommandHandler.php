<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;


class OrderConfirmCommandHandler extends AbstractOrderCommandHandler
{


    /**
     * @param OrderConfirmCommand $command
     *
     * @return void
     * @throws OrderException|Throwable
     */
    public function handle(OrderConfirmCommand $command) : void
    {

        $this->beginDatabaseTransaction();

        try {
            $order = $this->findByNo($command->orderNo);

            $order->confirm();

            $this->service->repository->update($order);

            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


    }


}
