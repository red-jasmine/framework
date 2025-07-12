<?php

declare(strict_types = 1);

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Commands;

use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Coupon\Domain\Repositories\CouponRepositoryInterface;
use RedJasmine\Coupon\Domain\Repositories\UserCouponRepositoryInterface;
use RedJasmine\Coupon\Domain\Services\CouponUserService;
use RedJasmine\Coupon\Exceptions\CouponException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class UserCouponReceiveCommandHandler extends CommandHandler
{
    public function __construct(
        protected UserCouponApplicationService $service,
        protected CouponRepositoryInterface $couponRepository,
        protected UserCouponRepositoryInterface $userCouponRepository,
        protected CouponUserService $couponUserService,
    ) {
    }

    /**
     * @param  UserCouponReceiveCommand  $command
     *
     * @return UserCoupon
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(UserCouponReceiveCommand $command) : UserCoupon
    {
        $this->beginDatabaseTransaction();

        try {
            // 1. 获取优惠券
            $coupon = $this->couponRepository->find($command->getKey());
            if (!$coupon) {
                throw new CouponException('优惠券不存在');
            }

            $userCoupon = $this->couponUserService->receive($coupon, $command->user);


            $this->userCouponRepository->store($userCoupon);


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