<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use Exception;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCardKeyReshipmentCommand;
use RedJasmine\Order\Domain\Models\OrderProductCardKey;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class RefundCardKeyReshipmentCommandHandler extends AbstractRefundCommandHandler
{


    /**
     * @param RefundCardKeyReshipmentCommand $command
     *
     * @return void
     * @throws Exception|Throwable
     */
    public function handle(RefundCardKeyReshipmentCommand $command) : void
    {


        $this->beginDatabaseTransaction();

        try {
            $refund              = $this->find($command->rid);
            $orderProductCardKey = OrderProductCardKey::newModel();

            $orderProductCardKey->seller_type      = $refund->seller_type;
            $orderProductCardKey->seller_id        = $refund->seller_id;
            $orderProductCardKey->buyer_type       = $refund->buyer_type;
            $orderProductCardKey->buyer_id         = $refund->buyer_id;
            $orderProductCardKey->order_id         = $refund->order_id;
            $orderProductCardKey->order_product_id = $refund->order_product_id;

            $orderProductCardKey->content      = $command->content;
            $orderProductCardKey->content_type = $command->contentType;
            $orderProductCardKey->num          = $command->num;
            $orderProductCardKey->status       = $command->status;
            $orderProductCardKey->source_type  = $command->sourceType;
            $orderProductCardKey->source_id    = $command->sourceId;

            $refund->cardKeyReshipment($orderProductCardKey);

            $this->refundRepository->update($refund);

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
