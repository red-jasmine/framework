<?php

namespace RedJasmine\Coupon\Application\Services\Coupon\Commands;

use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\Support\Foundation\Data\Data;
use Throwable;

class CouponDeleteCommandHandler extends CommandHandler
{
    public function __construct(
        protected CouponApplicationService $service
    ) {
    }

    /**
     * @param Data $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(Data $command): bool
    {
        $this->beginDatabaseTransaction();

        try {
            $model = $this->service->find(FindQuery::from(['id' => $command->getKey()]));
            $this->service->repository->delete($model);
            
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