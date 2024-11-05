<?php

namespace RedJasmine\Order\Application\Services\Handlers\Shipping;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderCardKeyShippingCommand;
use RedJasmine\Order\Domain\Models\OrderProductCardKey;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Services\OrderShippingService;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderCardKeyShippingCommandHandler extends AbstractOrderCommandHandler
{

    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected OrderShippingService     $orderShippingService
    )
    {

        parent::__construct($orderRepository);
    }


    public function handle(OrderCardKeyShippingCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->find($command->id);

            $orderProductCardKey                   = OrderProductCardKey::newModel();
            $orderProductCardKey->order_product_id = $command->orderProductId;
            $orderProductCardKey->content          = $command->content;
            $orderProductCardKey->content_type     = $command->contentType;
            $orderProductCardKey->num              = $command->num;
            $orderProductCardKey->status           = $command->status;
            $orderProductCardKey->source_type      = $command->sourceType;
            $orderProductCardKey->source_id        = $command->sourceId;

            $this->orderShippingService->cardKey($order, $orderProductCardKey);

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
