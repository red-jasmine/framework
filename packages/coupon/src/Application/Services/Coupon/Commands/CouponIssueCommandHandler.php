<?php

namespace RedJasmine\Coupon\Application\Services\Coupon\Commands;

use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Coupon\Domain\Repositories\UserCouponRepositoryInterface;
use RedJasmine\Coupon\Domain\Services\CouponUserService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class CouponIssueCommandHandler extends CommandHandler
{
    public function __construct(
        protected CouponApplicationService $service,
        protected CouponUserService $couponUserService,
        public UserCouponRepositoryInterface $repository,
    ) {
    }

    /**
     * @param  CouponIssueCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(CouponIssueCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            $model = $this->service->repository->find($command->getKey());

            $userCoupon = $this->couponUserService->issue($model, $command->user);

            $this->repository->store($userCoupon);

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