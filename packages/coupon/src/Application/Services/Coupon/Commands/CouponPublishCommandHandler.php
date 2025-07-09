<?php

namespace RedJasmine\Coupon\Application\Services\Coupon\Commands;

use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class CouponPublishCommandHandler extends CommandHandler
{
    public function __construct(
        protected CouponApplicationService $service
    ) {
    }

    /**
     * @param Data $command
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(Data $command): bool
    {
        $this->beginDatabaseTransaction();

        try {
            $model = $this->service->find(FindQuery::from(['id' => $command->getKey()]));
            $model->status = CouponStatusEnum::PUBLISHED;
            $this->service->repository->update($model);
            
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return true;
    }
}