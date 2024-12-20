<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Trades;

use Closure;
use Illuminate\Support\Collection;
use RedJasmine\Payment\Application\Commands\Trade\TradeReadyCommand;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentRouteService;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MerchantAppRepository;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * 创建预支付单
 */
class TradeReadyCommandHandler extends CommandHandler
{

    public function __construct(
        protected TradeRepositoryInterface $repository,
        protected MerchantAppRepository    $merchantAppRepository,
        protected PaymentRouteService      $paymentRouteService,
    )
    {
    }

    protected bool|Closure $hasDatabaseTransactions = false;

    /**
     * @param TradeReadyCommand $command
     * @return Collection
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(TradeReadyCommand $command) : Collection
    {

        $this->beginDatabaseTransaction();

        try {
            // 获取支付单
            $trade = $command->id ? $this->repository->find($command->id) : $this->repository->findByTradeNo($command->tradeNo);

            // 根据 支付环境 获取 支付方式
            $methods = $this->paymentRouteService->getMethods($trade, $command);

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
