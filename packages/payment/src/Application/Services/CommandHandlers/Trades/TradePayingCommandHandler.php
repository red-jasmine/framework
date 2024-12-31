<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Trades;

use RedJasmine\Payment\Application\Commands\Trade\TradePayingCommand;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * 发起支付
 */
class TradePayingCommandHandler extends AbstractTradeCommandHandler
{

    /**
     * @param TradePayingCommand $command
     *
     * @return ChannelTradeData
     * @throws AbstractException
     * @throws PaymentException
     * @throws Throwable
     */
    public function handle(TradePayingCommand $command) : ChannelTradeData
    {
        $this->beginDatabaseTransaction();
        try {
            // 获取支付单
            $trade       = $this->service->repository->findByNo($command->tradeNo);
            $environment = $command;
            // 根据 支付环境、支付方式、 选择 支付应用
            $channelApp = $this->service->paymentRouteService->getChannelApp($trade, $environment);
            // 根据应用 去支付渠道 创建支付单
            $channelProduct = $this->service->paymentRouteService->getChannelProduct($environment, $channelApp);
            // 去渠道创建 支付单
            $channelTrade = $this->service
                ->paymentChannelService->purchase($channelApp, $channelProduct, $trade, $environment);
            // 更新支付单状态
            $trade->paying($channelApp, $environment, $channelTrade);
            // 返回支付场景等信息
            $this->service->repository->update($trade);
            // 返回支付结果信息
            $this->commitDatabaseTransaction();

        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $channelTrade;

    }

}
