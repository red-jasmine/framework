<?php

namespace RedJasmine\Payment\Application\Services\Trade\Commands;

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
            $trade = $this->service->repository->findByNo($command->tradeNo);

            $environment = $command;

            // 根据 支付环境、支付方式、 选择 支付应用
            $channelApp = $this->service->tradeRoutingService->getChannelApp($trade, $environment);

            // 根据支付环境、支付方式、 选择 支付产品
            $channelProduct = $this->service->tradeRoutingService->getChannelProduct($environment, $channelApp);

            // 根据应用去渠道发起支付单
            $channelTrade = $this->service->paymentChannelService->purchase($channelApp, $channelProduct, $trade, $environment);

            // 交易设置为 支付中
            $trade->paying($channelApp, $environment, $channelTrade);

            // 更新数据库
            $this->service->repository->update($trade);

            // 提交数据库事务
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
