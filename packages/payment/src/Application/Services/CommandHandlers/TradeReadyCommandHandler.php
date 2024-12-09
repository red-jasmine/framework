<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers;

use Closure;
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
     * @return array
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(TradeReadyCommand $command) : array
    {

        $this->beginDatabaseTransaction();

        try {
            // 获取支付单
            $trade = $this->repository->find($command->id);

            // 根据 支付环境 获取 支付方式
            $methods = $this->paymentRouteService->getMethods($trade->merchantApp, $command);
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


        return [];

    }

}
