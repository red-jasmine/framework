<?php

namespace RedJasmine\Payment\Application\Services\Trade\Commands;

use Closure;
use RedJasmine\Payment\Domain\Data\Trades\PaymentMethod;
use RedJasmine\Payment\Domain\Data\Trades\PaymentTradeResult;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;


class TradeReadyCommandHandler extends AbstractTradeCommandHandler
{
    protected bool|Closure $hasDatabaseTransactions = false;

    /**
     * @param  TradeReadyCommand  $command
     *
     * @return PaymentTradeResult
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(TradeReadyCommand $command) : PaymentTradeResult
    {

        $this->beginDatabaseTransaction();

        try {
            // 获取支付单
            $trade = $this->service->repository->findByNo($command->tradeNo);

            // 根据 支付环境 获取 支付方式
            $methods = $this->service->tradeRoutingService->getMethods($trade, $command);

            $paymentTradeResult          = $this->service->getSdkResult($trade);
            $paymentTradeResult->methods = PaymentMethod::collect($methods->toArray());

            // 返回支付场景等信息
            $this->commitDatabaseTransaction();
            return $paymentTradeResult;
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $paymentTradeResult;

    }

}
