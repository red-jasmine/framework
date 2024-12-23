<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Trades;

use RedJasmine\Payment\Application\Commands\Trade\TradePaidCommand;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MerchantAppRepository;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;

class TradePaidCommandHandler extends CommandHandler
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
     * @param TradePaidCommand $command
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     * @throws PaymentException
     */
    public function handle(TradePaidCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {
            $trade = $this->repository->findByNo($command->tradeNo);

            $trade->paid($command);

            $this->repository->update($trade);


            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
        return true;

    }

}
