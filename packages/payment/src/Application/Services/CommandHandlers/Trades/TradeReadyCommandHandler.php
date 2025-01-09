<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Trades;

use Closure;
use Illuminate\Support\Collection;
use RedJasmine\Payment\Application\Commands\Trade\TradeReadyCommand;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;


class TradeReadyCommandHandler extends AbstractTradeCommandHandler
{
    protected bool|Closure $hasDatabaseTransactions = false;

    /**
     * @param  TradeReadyCommand  $command
     *
     * @return Collection
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(TradeReadyCommand $command) : Collection
    {

        $this->beginDatabaseTransaction();

        try {
            // 获取支付单
            $trade = $this->service->repository->findByNo($command->tradeNo);

            // 根据 支付环境 获取 支付方式
            $methods = $this->service->tradeRoutingService->getMethods($trade, $command);

            // 返回支付场景等信息
            $this->commitDatabaseTransaction();
            return $methods;
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $methods;

    }

}
