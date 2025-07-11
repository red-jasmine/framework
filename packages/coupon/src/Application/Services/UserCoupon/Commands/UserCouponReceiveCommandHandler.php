<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Commands;

use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Coupon\Domain\Repositories\CouponRepositoryInterface;
use RedJasmine\Coupon\Domain\Repositories\UserCouponRepositoryInterface;
use RedJasmine\Coupon\Exceptions\CouponException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class UserCouponReceiveCommandHandler extends CommandHandler
{
    public function __construct(
        protected UserCouponApplicationService $service,
        protected CouponRepositoryInterface $couponRepository,
        protected UserCouponRepositoryInterface $userCouponRepository
    ) {
    }

    /**
     * @param UserCouponReceiveCommand $command
     * @return UserCoupon
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(UserCouponReceiveCommand $command): UserCoupon
    {
        $this->beginDatabaseTransaction();

        try {
            // 1. 获取优惠券
            $coupon = $this->couponRepository->find($command->couponId);
            if (!$coupon) {
                throw new CouponException('优惠券不存在');
            }

            // 2. 验证优惠券可领取性
            if (!$coupon->canCollect([
                'user_id' => $command->user->getID(),
                'channel' => $command->channel,
                'invite_code' => $command->inviteCode,
                'extra' => $command->extra
            ])) {
                throw new CouponException('优惠券不可领取');
            }

            // 3. 检查用户是否已领取过此优惠券
            $existingUserCoupon = UserCoupon::where('coupon_id', $command->couponId)
                ->where('user_id', $command->user->getID())
                ->where('user_type', $command->user->getType())
                ->first();

            if ($existingUserCoupon) {
                throw new CouponException('您已领取过此优惠券');
            }

            // 4. 创建用户优惠券记录
            $userCoupon = new UserCoupon();
            $userCoupon->coupon_id = $command->couponId;
            $userCoupon->user_id = $command->user->getID();
            $userCoupon->user_type = $command->user->getType();
            $userCoupon->owner_id = $command->owner->getID();
            $userCoupon->owner_type = $command->owner->getType();
            $userCoupon->expire_time = $coupon->getExpireTime();
            $userCoupon->channel = $command->channel;
            $userCoupon->invite_code = $command->inviteCode;
            $userCoupon->extra = $command->extra;
            $userCoupon->status = 'available';

            $this->userCouponRepository->store($userCoupon);

            // 5. 更新优惠券发放统计
            $coupon->increment('total_issued');
            $this->couponRepository->update($coupon);

            $this->commitDatabaseTransaction();
            
            return $userCoupon;
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}