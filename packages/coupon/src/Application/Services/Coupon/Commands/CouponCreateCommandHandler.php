<?php

namespace RedJasmine\Coupon\Application\Services\Coupon\Commands;

use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class CouponCreateCommandHandler extends CommandHandler
{
    public function __construct(
        protected CouponApplicationService $service
    ) {
    }

    /**
     * @param CouponCreateCommand $command
     * @return Coupon
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(CouponCreateCommand $command): Coupon
    {
        $this->beginDatabaseTransaction();

        try {
            $model = $this->service->newModel();
            $model = $this->service->transformer->transform($command, $model);
            $model->owner = $command->owner;
            
            $this->service->repository->store($model);
            
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