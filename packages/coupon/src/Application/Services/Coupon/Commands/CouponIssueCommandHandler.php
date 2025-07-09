<?php

namespace RedJasmine\Coupon\Application\Services\Coupon\Commands;

use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class CouponIssueCommandHandler extends CommandHandler
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
            // TODO: 实现优惠券发放逻辑
            // 1. 检查优惠券是否可发放
            // 2. 创建用户优惠券实例
            // 3. 更新发放统计
            
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