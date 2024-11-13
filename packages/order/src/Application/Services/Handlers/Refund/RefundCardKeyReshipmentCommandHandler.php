<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use Exception;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCardKeyReshipmentCommand;
use RedJasmine\Order\Domain\Models\OrderCardKey;
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
            $refund              = $this->find($command->id);
            $orderProductCardKey = OrderCardKey::newModel();



            $orderProductCardKey->content      = $command->content;
            $orderProductCardKey->content_type = $command->contentType;
            $orderProductCardKey->quantity          = $command->quantity;
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
