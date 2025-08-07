<?php

namespace RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands;

use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeResult;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\PointsExchangeOrderApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;


/**
 * @property PointsExchangeOrderApplicationService $service
 */
class PointsExchangeOrderPaidCommandHandler extends CommandHandler
{

    public function __construct(

        protected PointsExchangeOrderApplicationService $service
    ) {
    }


    /**
     * 发起支付
     *
     * @param  PointsExchangeOrderPaidCommand  $command
     *
     * @return bool
     * @throws Throwable
     */
    public function handle(PointsExchangeOrderPaidCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            $pointsExchangeOrder = $this->service->repository->findByOuterOrderNo($command->orderNo);

            $result = $this->service->pointsExchangeService->paid($pointsExchangeOrder, $command);

            $this->service->repository->update($pointsExchangeOrder);
            $this->commitDatabaseTransaction();
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