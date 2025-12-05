<?php

namespace RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands;

use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeResult;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\PointsExchangeOrderApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;


/**
 * @property PointsExchangeOrderApplicationService $service
 */
class PointsExchangeOrderPayCommandHandler extends CommandHandler
{

    public function __construct(

        protected PointsExchangeOrderApplicationService $service
    ) {
    }


    /**
     * 发起支付
     *
     * @param  PointsExchangeOrderPayCommand  $command
     *
     * @return PaymentTradeResult
     * @throws Throwable
     */
    public function handle(PointsExchangeOrderPayCommand $command) : PaymentTradeResult
    {
        $this->beginDatabaseTransaction();

        try {


            $pointsExchangeOrder = $this->service->repository->findByOuterOrderNo($command->getKey());

            $result = $this->service->pointsExchangeService->pay($pointsExchangeOrder);

            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $result;
    }

}