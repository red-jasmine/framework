<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel\Commands;

use RedJasmine\Payment\Application\Services\PaymentChannel\PaymentChannelApplicationService;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class ChannelTransferCreateCommandHandler extends CommandHandler
{

    public function __construct(protected PaymentChannelApplicationService $service)
    {

    }

    /**
     * @param ChannelTransferCreateCommand $command
     *
     * @return bool
     * @throws BaseException
     * @throws PaymentException
     * @throws Throwable
     */
    public function handle(ChannelTransferCreateCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $transfer = $this->service->transferRepository->findByNo($command->transferNo);

            $channelApp     = $this->service->channelAppRepository->find($transfer->system_channel_app_id);
            $channelProduct = $this->service->channelProductRepository->findByCode($transfer->channel_code,
                                                                                   $transfer->channel_product_code);
            $this->service->paymentChannelService->transfer($channelApp, $channelProduct, $transfer);

            $this->service->tradeRepository->update($transfer);

            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
        return true;
    }

}
