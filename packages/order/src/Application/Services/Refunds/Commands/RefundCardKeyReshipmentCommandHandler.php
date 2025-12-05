<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use Exception;
use RedJasmine\Order\Domain\Models\OrderCardKey;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class RefundCardKeyReshipmentCommandHandler extends AbstractRefundCommandHandler
{


    /**
     * @param  RefundCardKeyReshipmentCommand  $command
     *
     * @return void
     * @throws Exception|Throwable
     */
    public function handle(RefundCardKeyReshipmentCommand $command) : void
    {


        $this->beginDatabaseTransaction();

        try {
            $refund              = $this->findByNo($command->refundNo);
            $orderProductCardKey = OrderCardKey::make();


            $orderProductCardKey->content      = $command->content;
            $orderProductCardKey->content_type = $command->contentType;
            $orderProductCardKey->quantity     = $command->quantity;
            $orderProductCardKey->status       = $command->status;
            $orderProductCardKey->source_type  = $command->sourceType;
            $orderProductCardKey->source_id    = $command->sourceId;

            $refund->cardKeyReshipment($orderProductCardKey);

            $this->service->repository->update($refund);

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
