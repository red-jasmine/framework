<?php

namespace RedJasmine\Coupon\Application\Services\Coupon\Commands;

use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Support\Application\Commands\CommandHandler;

use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class CouponPublishCommandHandler extends CommandHandler
{
    public function __construct(
        protected CouponApplicationService $service
    ) {
    }

    /**
     * @param  CouponPublishCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(CouponPublishCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {
            $model = $this->service->repository->find($command->getKey());

            $model->publish();

            $this->service->repository->update($model);

            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return true;
    }
}