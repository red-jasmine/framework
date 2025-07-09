<?php

namespace RedJasmine\Coupon\Application\Services\Coupon\Commands;

use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class CouponUpdateCommandHandler extends CommandHandler
{
    public function __construct(
        protected CouponApplicationService $service
    ) {
    }

    /**
     * @param CouponUpdateCommand $command
     * @return Coupon
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(CouponUpdateCommand $command): Coupon
    {
        $this->beginDatabaseTransaction();

        try {
            $model = $this->service->find(FindQuery::from(['id' => $command->getKey()]));
            $model = $this->service->transformer->transform($command, $model);
            
            $this->service->repository->update($model);
            
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $model;
    }
}