<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Notify;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use RedJasmine\Payment\Application\Commands\Notify\ChannelNotifyTradeCommand;
use RedJasmine\Payment\Domain\Gateway\NotifyResponseInterface;
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
        $response   = app(PaymentChannelService::class)->notifyResponse($channelApp);
        try {
            $channelTradeData = app(PaymentChannelService::class)->completePurchase($channelApp, $command->content);

        } catch (Throwable $throwable) {
            report($throwable);
            return $response->fail();
        }
        try {
            $this->beginDatabaseTransaction();
            $trade = $this->repository->find($channelTradeData->id);
            $trade->paid($channelTradeData);

            $this->repository->update($trade);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            Log::info('Payment-Notify', [ 'message' => $exception->getMessage() ]);
            return $response->fail();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            report($throwable);
            return $response->fail();
        }


        return $response->success();


    }

}
