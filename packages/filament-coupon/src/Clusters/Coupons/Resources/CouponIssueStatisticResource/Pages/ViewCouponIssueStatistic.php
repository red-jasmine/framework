<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponIssueStatisticResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponIssueStatisticResource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ViewCouponIssueStatistic extends ViewRecord
{
    use ResourcePageHelper;

    protected static string $resource = CouponIssueStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // 统计数据不支持编辑
        ];
    }
} 