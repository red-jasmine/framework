<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Trades;

use RedJasmine\Payment\Application\Commands\Trade\TradePayingCommand;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Gateway\Data\PurchaseResult;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Payment\Domain\Services\PaymentRouteService;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MerchantAppRepository;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * 发起支付
 */
class TradePayingCommandHandler extends CommandHandler
{

    public function __construct(
        protected TradeRepositoryInterface $repository,
        protected MerchantAppRepository    $merchantAppRepository,
        protected PaymentRouteService      $paymentRouteService,

    )
    {
    }


    /**
     * @param TradePayingCommand $command
     * @return PurchaseResult
     * @throws AbstractException
     * @throws PaymentException
     * @throws Throwable
     */
    public function handle(TradePayingCommand $command) : PurchaseResult
    {
        $this->beginDatabaseTransaction();
        try {
            // 获取支付单
            $trade       = $this->repository->find($command->id);
            $environment = $command;
            // 根据 支付环境、支付方式、 选择 支付应用
            $channelApp = $this->paymentRouteService->getChannelApp($trade, $environment);
            // 根据应用 去支付渠道 创建支付单
            $channelProduct = $this->paymentRouteService->getChannelProduct($environment, $channelApp);
            // 去渠道创建 支付单
            $result = app(PaymentChannelService::class)->createTrade($channelApp, $channelProduct, $trade, $environment);

            if (!$result->isSuccessFul()) {
                throw PaymentException::newFromCodes(PaymentException::TRADE_PAYING);
            }
            // 更新支付单状态
            $trade->paying();
            // 返回支付场景等信息

            $this->repository->update($trade);
            // 返回支付结果信息

            $this->commitDatabaseTransaction();

            return $result;

        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $result;

    }

}
