<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class OrderSellerCustomStatusCommandHandler extends AbstractOrderCommandHandler
{


    /**
     * @param OrderSellerCustomStatusCommand $command
     *
     * @return void
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(OrderSellerCustomStatusCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->findByNo($command->orderNo);

            $order->setSellerCustomStatus($command->sellerCustomStatus, $command->orderProductNo);

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
