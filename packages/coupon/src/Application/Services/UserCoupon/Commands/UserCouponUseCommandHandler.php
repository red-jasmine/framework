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

    /**
     * 验证优惠券可用性
     */
    private function validateCouponUsability(UserCoupon $userCoupon, UserCouponUseCommand $command) : void
    {
        // TODO: 实现优惠券可用性验证逻辑
        // 1. 检查优惠券状态
        // 2. 检查使用时间
        // 3. 检查使用条件
    }

    /**
     * 创建使用记录
     */
    private function createUsageRecord(UserCoupon $userCoupon, UserCouponUseCommand $command) : void
    {
        // TODO: 实现创建使用记录逻辑
        // 1. 创建 CouponUsage 记录
        // 2. 记录使用详情
    }
}