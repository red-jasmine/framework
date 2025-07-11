<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\Application\Services\UserCoupon;

use RedJasmine\Coupon\Application\Services\UserCoupon\Commands\UserCouponReceiveCommand;
use RedJasmine\Coupon\Application\Services\UserCoupon\Commands\UserCouponReceiveCommandHandler;
use RedJasmine\Coupon\Application\Services\UserCoupon\Commands\UserCouponUseCommand;
use RedJasmine\Coupon\Application\Services\UserCoupon\Commands\UserCouponUseCommandHandler;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Coupon\Domain\Repositories\UserCouponReadRepositoryInterface;
use RedJasmine\Coupon\Domain\Repositories\UserCouponRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 用户优惠券应用服务
 * 
 * @see UserCouponUseCommandHandler::handle()
 * @method bool use(UserCouponUseCommand $command)
 * @see UserCouponReceiveCommandHandler::handle()
 * @method UserCoupon receive(UserCouponReceiveCommand $command)
 */
class UserCouponApplicationService extends ApplicationService
{
    /**
     * Hook前缀配置
     * @var string
     */
    public static string $hookNamePrefix = 'coupon.application.user_coupon';

    protected static string $modelClass = UserCoupon::class;

    public function __construct(
        public UserCouponRepositoryInterface $repository,
        public UserCouponReadRepositoryInterface $readRepository
    ) {
    }

    protected static $macros = [
        'use' => UserCouponUseCommandHandler::class,
        'receive' => UserCouponReceiveCommandHandler::class,
    ];
}