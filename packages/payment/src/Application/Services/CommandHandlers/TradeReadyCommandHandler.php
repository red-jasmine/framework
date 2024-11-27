<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers;

use Closure;
use RedJasmine\Payment\Application\Commands\Trade\TradeReadyCommand;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentPlatformRouteService;
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
        protected TradeRepositoryInterface    $repository,
        protected MerchantAppRepository       $merchantAppRepository,
        protected PaymentPlatformRouteService $paymentPlatformRouteService,

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

            // 根据 支付环境 获取 支付平台
            $platforms = $this->paymentPlatformRouteService->getPlatforms($trade->merchantApp, $command);
            // 返回支付方式等信息
            $this->commitDatabaseTransaction();
            return $platforms;
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
