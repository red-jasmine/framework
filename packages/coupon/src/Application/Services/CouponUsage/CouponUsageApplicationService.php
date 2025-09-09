<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\Application\Services\CouponUsage;

use RedJasmine\Coupon\Domain\Models\CouponUsage;
use RedJasmine\Coupon\Domain\Repositories\CouponUsageRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 优惠券使用记录应用服务
 *
 * 使用统一的仓库接口，支持读写操作
 * 负责处理优惠券使用记录相关的业务逻辑
 */
class CouponUsageApplicationService extends ApplicationService
{
    /**
     * Hook前缀配置
     * @var string
     */
    public static string $hookNamePrefix = 'coupon.application.coupon_usage';

    protected static string $modelClass = CouponUsage::class;

    public function __construct(
        public CouponUsageRepositoryInterface $repository
    ) {
    }

    protected static $macros = [
        // 可以在这里添加自定义处理器
    ];

    /**
     * 获取用户的使用记录统计
     *
     * @param int $userId
     * @param string $userType
     * @return array
     */
    public function getUserUsageStatistics(int $userId, string $userType): array
    {
        $baseQuery = $this->repository->query()
                                     ->where('user_id', $userId)
                                     ->where('user_type', $userType);

        return [
            'total_count' => $baseQuery->clone()->count(),
            'total_discount_amount' => $baseQuery->clone()->sum('discount_amount'),
            'total_final_discount_amount' => $baseQuery->clone()->sum('final_discount_amount'),
            'average_discount_amount' => $baseQuery->clone()->avg('discount_amount'),
            'max_discount_amount' => $baseQuery->clone()->max('discount_amount'),
            'min_discount_amount' => $baseQuery->clone()->min('discount_amount'),
        ];
    }

    /**
     * 获取按月统计的使用记录
     *
     * @param int $userId
     * @param string $userType
     * @param int $months
     * @return array
     */
    public function getUserMonthlyStatistics(int $userId, string $userType, int $months = 12): array
    {
        $statistics = [];
        $baseQuery = $this->repository->query()
                                     ->where('user_id', $userId)
                                     ->where('user_type', $userType);

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();

            $statistics[] = [
                'month' => $monthStart->format('Y-m'),
                'count' => $baseQuery->clone()
                                   ->whereBetween('used_at', [$monthStart, $monthEnd])
                                   ->count(),
                'discount_amount' => $baseQuery->clone()
                                              ->whereBetween('used_at', [$monthStart, $monthEnd])
                                              ->sum('final_discount_amount'),
            ];
        }

        return $statistics;
    }

    /**
     * 获取某个优惠券的使用统计
     *
     * @param int $couponId
     * @return array
     */
    public function getCouponUsageStatistics(int $couponId): array
    {
        $baseQuery = $this->repository->query()
                                     ->where('coupon_id', $couponId);

        return [
            'total_count' => $baseQuery->clone()->count(),
            'total_discount_amount' => $baseQuery->clone()->sum('discount_amount'),
            'total_final_discount_amount' => $baseQuery->clone()->sum('final_discount_amount'),
            'average_discount_amount' => $baseQuery->clone()->avg('discount_amount'),
            'unique_users' => $baseQuery->clone()->distinct('user_id')->count('user_id'),
        ];
    }

    /**
     * 获取指定时间范围内的使用记录
     *
     * @param int $userId
     * @param string $userType
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     * @return array
     */
    public function getUserUsageByDateRange(int $userId, string $userType, $startDate, $endDate): array
    {
        $baseQuery = $this->repository->query()
                                     ->where('user_id', $userId)
                                     ->where('user_type', $userType)
                                     ->whereBetween('used_at', [$startDate, $endDate]);

        return [
            'count' => $baseQuery->clone()->count(),
            'total_discount_amount' => $baseQuery->clone()->sum('final_discount_amount'),
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ];
    }
}
