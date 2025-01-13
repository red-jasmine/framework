<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel\Commands;

use RedJasmine\Payment\Application\Services\PaymentChannel\PaymentChannelHandlerService;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class ChannelTransferCreateCommandHandler extends CommandHandler
{

    public function __construct(protected PaymentChannelHandlerService $service)
    {

    }

    /**
     * @param ChannelTransferCreateCommand $command
     *
     * @return bool
     * @throws AbstractException
     * @throws PaymentException
     * @throws Throwable
     */
    public function handle(ChannelTransferCreateCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $transfer = $this->service->transferRepository->findByNo($command->transferNo);

            $channelApp     = $this->service->channelAppRepository->find($transfer->payment_channel_app_id);
            $channelProduct = $this->service->channelProductRepository->findByCode($transfer->channel_code,
                                                                                   $transfer->channel_product_code);
            $this->service->paymentChannelService->transfer($channelApp, $channelProduct, $transfer);

            $this->service->tradeRepository->update($transfer);

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
