<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel\Commands;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use RedJasmine\Payment\Application\Services\AsyncNotify\Commands\ChannelNotifyTradeCommand;
use RedJasmine\Payment\Application\Services\PaymentChannel\PaymentChannelHandlerService;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class ChannelTradeNotifyCommandHandler extends CommandHandler
{


    public function __construct(protected PaymentChannelHandlerService $service)
    {

    }

    /**
     * @param ChannelNotifyTradeCommand $command
     *
     * @return Response
     */
    public function handle(ChannelNotifyTradeCommand $command) : Response
    {
        $channelApp = $this->service->channelAppRepository->find($command->appId);
        // 获取渠道标准响应
        $response = $this->service->paymentChannelService->notifyResponse($channelApp);

        try {
            // 渠道完成支付、获取渠道订单信息
            $channelTradeData = $this->service
                ->paymentChannelService
                ->completePurchase($channelApp, $command->content);

            // 交易已支付
            $this->handleTradePaid($channelTradeData);
        } catch (Throwable $throwable) {
            report($throwable);
            return $response->fail();
        }
        return $response->success();

    }


    /**
     * @param ChannelTradeData $channelTradeData
     *
     * @return true
     * @throws AbstractException
     * @throws PaymentException
     * @throws Throwable
     */
    protected function handleTradePaid(ChannelTradeData $channelTradeData) : bool
    {
        try {

            $this->beginDatabaseTransaction();

            $trade = $this->service->tradeRepository->findByNo($channelTradeData->tradeNo);

            if ($trade->isPaid()) {
                $this->commitDatabaseTransaction();
                return true;
            }

            $trade->paid($channelTradeData);

            $this->service->tradeRepository->update($trade);

            $this->commitDatabaseTransaction();

        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            Log::info('Payment-Notify', [ 'message' => $exception->getMessage() ]);
            throw $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            report($throwable);
            throw $throwable;
        }

        return true;

    }

}
