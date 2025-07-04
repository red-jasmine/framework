<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;


use RedJasmine\Order\Application\Mappers\OrderAddressMapper;
use RedJasmine\Order\Application\Mappers\OrderMapper;
use RedJasmine\Order\Application\Mappers\OrderProductMapper;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderCreateCommandHandler extends AbstractOrderCommandHandler
{


    /**
     * @param  OrderCreateCommand  $command
     *
     * @return Order
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(OrderCreateCommand $command) : Order
    {

        $order = Order::make(
            [
                'currency' => $command->currency,
                'app_id'   => $command->appId,
                'seller'   => $command->seller,
                'buyer'    => $command->buyer,

            ]
        );


        $this->context->setModel($order);
        $this->context->setCommand($command);


        $this->service->transformer->transform($command, $order);

        $this->beginDatabaseTransaction();

        try {


            $this->service->hook('create.validate', $command, fn() => $this->validate($command));

            $this->service->hook('create.fill', $command, fn() => null);


            $order->createOrder();

            $this->service->repository->store($order);


            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $order;

    }


    protected function validate(OrderCreateCommand $command) : void
    {

    }


}
