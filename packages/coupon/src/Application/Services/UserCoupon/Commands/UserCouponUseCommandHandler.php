<?php

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Commands;

use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Coupon\Domain\Services\CouponUserService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class UserCouponUseCommandHandler extends CommandHandler
{
    public function __construct(
        protected UserCouponApplicationService $service,
        protected CouponUserService $couponUserService,
    ) {
    }

    /**
     * @param  UserCouponUseCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(UserCouponUseCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            // 1. 查找用户优惠券
            $userCoupon = $this->service->repository->findByNoLock($command->getKey());
            // 调用领域服务的使用
            $this->couponUserService->use($userCoupon, $command->usages);

            $this->service->repository->update($userCoupon);

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