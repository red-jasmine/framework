<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel\Commands;

use RedJasmine\Payment\Application\Services\PaymentChannel\PaymentChannelHandlerService;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class ChannelRefundCreateCommandHandler extends CommandHandler
{

    public function __construct(protected PaymentChannelHandlerService $service)
    {

    }

    /**
     * @param ChannelRefundCreateCommand $command
     * @return bool
     * @throws AbstractException
     * @throws PaymentException
     * @throws Throwable
     */
    public function handle(\RedJasmine\Payment\Application\Services\PaymentChannel\Commands\ChannelRefundCreateCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $refund = $this->service->refundRepository->findByNo($command->refundNo);

            if (!$refund->isAllowProcessing()) {
                throw new PaymentException('不支持渠道处理退款', PaymentException::REFUND_STATUS_ERROR);
            }
            $channelApp = $this->service->channelAppRepository->find($refund->payment_channel_app_id);
            // 调用服务
            try {
                $this->service->paymentChannelService->refund($channelApp, $refund);
                $refund->processing();
            } catch (AbstractException $exception) {
                $refund->abnormal($exception->getMessage());
            }
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
