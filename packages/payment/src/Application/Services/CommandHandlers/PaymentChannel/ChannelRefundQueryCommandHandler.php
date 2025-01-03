<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\PaymentChannel;

use RedJasmine\Payment\Application\Commands\PaymentChannel\ChannelRefundQueryCommand;
use RedJasmine\Payment\Application\Services\PaymentChannelHandlerService;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;

class ChannelRefundQueryCommandHandler extends CommandHandler
{
    public function __construct(protected PaymentChannelHandlerService $service)
    {

    }


    public function handle(ChannelRefundQueryCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $refund = $this->service->refundRepository->findByNo($command->refundNo);

            $channelApp = $this->service->channelAppRepository->find($refund->payment_channel_app_id);

            $channelRefundData = $this->service->paymentChannelService->refundQuery($channelApp, $refund);

            $refund->setChannelQueryResult($channelRefundData);

            $this->service->refundRepository->update($refund);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
        return true;
    }
}
