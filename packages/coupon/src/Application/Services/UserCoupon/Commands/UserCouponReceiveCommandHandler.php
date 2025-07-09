<?php

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Commands;

use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class UserCouponReceiveCommandHandler extends CommandHandler
{
    public function __construct(
        protected UserCouponApplicationService $service
    ) {
    }

    /**
     * @param UserCouponReceiveCommand $command
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(UserCouponReceiveCommand $command): bool
    {
        $this->beginDatabaseTransaction();

        try {
            // TODO: 实现用户优惠券领取逻辑
            // 1. 验证优惠券可领取性
            // 2. 检查用户领取限制
            // 3. 创建用户优惠券记录
            // 4. 更新优惠券发放统计
            
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