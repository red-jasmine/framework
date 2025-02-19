<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Models\OrderCardKey;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderCardKeyShippingCommandHandler extends AbstractOrderCommandHandler
{



    public function handle(OrderCardKeyShippingCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->find($command->id);

            $orderProductCardKey                   = OrderCardKey::make();
            $orderProductCardKey->order_product_id = $command->orderProductId;
            $orderProductCardKey->content          = $command->content;
            $orderProductCardKey->content_type     = $command->contentType;
            $orderProductCardKey->quantity         = $command->quantity;
            $orderProductCardKey->status           = $command->status;
            $orderProductCardKey->source_type      = $command->sourceType;
            $orderProductCardKey->source_id        = $command->sourceId;

            $this->service->orderShippingService->cardKey($order, $orderProductCardKey);

            $this->service->repository->update($order);

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
