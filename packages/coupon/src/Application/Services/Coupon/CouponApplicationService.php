<?php

namespace RedJasmine\Coupon\Application\Services\Coupon;

use RedJasmine\Coupon\Application\Services\Coupon\Commands\CouponCreateCommandHandler;
use RedJasmine\Coupon\Application\Services\Coupon\Commands\CouponDeleteCommandHandler;
use RedJasmine\Coupon\Application\Services\Coupon\Commands\CouponUpdateCommandHandler;
use RedJasmine\Coupon\Application\Services\Coupon\Commands\CouponPublishCommandHandler;
use RedJasmine\Coupon\Application\Services\Coupon\Commands\CouponPauseCommandHandler;
use RedJasmine\Coupon\Application\Services\Coupon\Commands\CouponIssueCommandHandler;
use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Repositories\CouponReadRepositoryInterface;
use RedJasmine\Coupon\Domain\Repositories\CouponRepositoryInterface;
use RedJasmine\Coupon\Domain\Transformers\CouponTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 优惠券应用服务
 *
 * @see CouponCreateCommandHandler::handle()
 * @method Coupon create(CouponCreateCommand $command)
 * @see CouponUpdateCommandHandler::handle()
 * @method void update(CouponUpdateCommand $command)
 * @see CouponDeleteCommandHandler::handle()
 * @method void delete(CouponDeleteCommand $command)
 * @see CouponPublishCommandHandler::handle()
 * @method void publish(CouponPublishCommand $command)
 * @see CouponPauseCommandHandler::handle()
 * @method void pause(CouponPauseCommand $command)
 * @see CouponIssueCommandHandler::handle()
 * @method void issue(CouponIssueCommand $command)
 */
class CouponApplicationService extends ApplicationService
{
    /**
     * Hook前缀配置
     * @var string
     */
    public static string $hookNamePrefix = 'coupon.application.coupon';

    protected static string $modelClass = Coupon::class;

    public function __construct(
        public CouponRepositoryInterface $repository,
        public CouponReadRepositoryInterface $readRepository,
        public CouponTransformer $transformer
    ) {
    }

    public function getDefaultModelWithInfo() : array
    {
        return ['issueStat'];
    }

    protected static $macros = [
        'create'  => CouponCreateCommandHandler::class,
        'update'  => CouponUpdateCommandHandler::class,
        'delete'  => CouponDeleteCommandHandler::class,
        'publish' => CouponPublishCommandHandler::class,
        'pause'   => CouponPauseCommandHandler::class,
        'issue'   => CouponIssueCommandHandler::class,
    ];
} 