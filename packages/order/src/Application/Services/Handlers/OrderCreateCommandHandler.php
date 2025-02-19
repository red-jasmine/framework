<?php

namespace RedJasmine\Order\Application\Services\Handlers;


use RedJasmine\Order\Application\Mappers\OrderAddressMapper;
use RedJasmine\Order\Application\Mappers\OrderMapper;
use RedJasmine\Order\Application\Mappers\OrderProductMapper;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Transformer\OrderTransformer;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderCreateCommandHandler extends AbstractOrderCommandHandler
{


    /**
     * @param OrderCreateCommand $command
     * @return Order
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(OrderCreateCommand $command) : Order
    {
        $order = app(OrderTransformer::class)->transform($command);

        $this->setModel($order);

        $this->beginDatabaseTransaction();

        try {


            $this->getService()->hook('create.validate', $command, fn() => $this->validate($command));

            $this->getService()->hook('create.fill', $command, fn() => null);


            $order->create();

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
