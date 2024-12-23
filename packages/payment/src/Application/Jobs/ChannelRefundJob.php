<?php

namespace RedJasmine\Payment\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\CanUseDatabaseTransactions;
use Throwable;

class ChannelRefundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use CanUseDatabaseTransactions;

    public function __construct(private readonly string $refundNo)
    {
    }

    /**
     * @return void
     * @throws AbstractException
     * @throws Throwable
     * @throws PaymentException
     */
    public function handle() : void
    {
        // 异步请求退款

        $this->beginDatabaseTransaction();

        try {
            $refund = app(RefundRepositoryInterface::class)->findByNo($this->refundNo);

            $channelApp = app(ChannelAppRepositoryInterface::class)->find($refund->payment_channel_app_id);
            // 调用服务
            app(PaymentChannelService::class)->refund($channelApp, $refund);

            $refund->refunding();

            app(RefundRepositoryInterface::class)->update($refund);

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
