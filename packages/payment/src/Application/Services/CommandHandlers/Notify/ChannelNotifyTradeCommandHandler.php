<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Notify;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use RedJasmine\Payment\Application\Commands\Notify\ChannelNotifyTradeCommand;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MerchantAppRepository;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class ChannelNotifyTradeCommandHandler extends CommandHandler
{
    public function __construct(
        protected TradeRepositoryInterface      $repository,
        protected ChannelAppRepositoryInterface $channelAppRepository,
        protected MerchantAppRepository         $merchantAppRepository,
        protected PaymentChannelService         $paymentChannelService,
    )
    {
    }

    /**
     * @param ChannelNotifyTradeCommand $command
     * @return Response
     */
    public function handle(ChannelNotifyTradeCommand $command) : Response
    {
        $channelApp = $this->channelAppRepository->find($command->appId);
        // 获取渠道标准响应
        $response = $this->paymentChannelService->notifyResponse($channelApp);

        try {
            // 渠道完成支付、获取渠道订单信息
            $channelTradeData = $this->paymentChannelService->completePurchase($channelApp, $command->content);
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
     * @return true
     * @throws AbstractException
     * @throws PaymentException
     * @throws Throwable
     */
    protected function handleTradePaid(ChannelTradeData $channelTradeData) : bool
    {
        try {

            $this->beginDatabaseTransaction();

            $trade = $this->repository->findByNo($channelTradeData->tradeNo);

            $trade->paid($channelTradeData);

            $this->repository->update($trade);

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
