<?php

namespace RedJasmine\Order\Application\Services\Handlers\Shipping;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderDummyShippingCommand;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Services\OrderShippingService;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderDummyShippingCommandHandler extends AbstractOrderCommandHandler
{

    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected OrderShippingService     $orderShippingService
    )
    {

        parent::__construct($orderRepository);
    }


    public function handle(OrderDummyShippingCommand $command) : void
    {


        $this->beginDatabaseTransaction();

        try {
            $order = $this->find($command->id);
            $this->orderShippingService->dummy($order, $command->orderProductId, $command->isFinished);

            $this->orderRepository->update($order);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

    }


}
