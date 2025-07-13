<?php

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Commands;

use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class UserCouponUseCommandHandler extends CommandHandler
{
    public function __construct(
        protected UserCouponApplicationService $service
    ) {
    }

    /**
     * @param UserCouponUseCommand $command
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(UserCouponUseCommand $command): bool
    {
        $this->beginDatabaseTransaction();

        try {

            // 1. 查找用户优惠券
            $userCoupon = $this->service->repository->findByNo($command->getKey());

            // 2. 验证优惠券可用性
            $this->validateCouponUsability($userCoupon, $command);
            
            // 3. 创建使用记录
            $this->createUsageRecord($userCoupon, $command);
            
            // 4. 更新优惠券状态
            $userCoupon->use($command->orderNo);
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
    private function validateCouponUsability($userCoupon, UserCouponUseCommand $command): void
    {
        // TODO: 实现优惠券可用性验证逻辑
        // 1. 检查优惠券状态
        // 2. 检查使用时间
        // 3. 检查使用条件
    }

    /**
     * 创建使用记录
     */
    private function createUsageRecord($userCoupon, UserCouponUseCommand $command): void
    {
        // TODO: 实现创建使用记录逻辑
        // 1. 创建 CouponUsage 记录
        // 2. 记录使用详情
    }
}