<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel\Commands;

use RedJasmine\Payment\Application\Services\CommandHandlers\PaymentChannel\Throwable;
use RedJasmine\Payment\Application\Services\PaymentChannel\PaymentChannelHandlerService;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;

class ChannelRefundQueryCommandHandler extends CommandHandler
{
    public function __construct(protected PaymentChannelHandlerService $service)
    {

    }


    public function handle(\RedJasmine\Payment\Application\Services\PaymentChannel\Commands\ChannelRefundQueryCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $refund = $this->service->refundRepository->findByNo($command->refundNo);

            $channelApp = $this->service->channelAppRepository->find($refund->system_channel_app_id);

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
